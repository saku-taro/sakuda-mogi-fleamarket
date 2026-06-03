<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Stripe\Stripe;
use Stripe\Checkout\Session;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);

        if ($item->trading_status === 1) {
            return redirect()->route('item.index');
        }

        // 1. 住所変更中かどうかを確認（pullで値を取得しつつ削除）
        $isEditing = session()->pull('is_editing_address', false);

        // 2. 住所変更中ではない場合のみ、商品IDを比較してセッションをクリアする
        if (!$isEditing && session('purchase_item_id') != $item_id) {
            session()->forget([
                'payment_method',
                'shipping_postcode',
                'shipping_address',
                'shipping_building',
            ]);
        }

        // 3. 現在のアイテムIDを保存
        session(['purchase_item_id' => $item_id]);

        $user = Auth::user();

        return view('item.purchase', compact('item', 'user'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        // テスト環境ならStripe通信を飛ばす
        if (app()->environment('testing')) {
            // ダミーのセッションURLを返す
            $redirectUrl = route('purchase.success', ['item_id' => $item_id]);
        } else {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $session = Session::create([
                'payment_method_types' => $request->payment_method === 'カード支払い' ? ['card'] : ['konbini'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => ['name' => $item->name],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                // 決済成功時に戻ってくるURL
                'cancel_url' => route('purchase.show', ['item_id' => $item_id]),

                'success_url' => route('purchase.success', ['item_id' => $item_id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'metadata' => [
                    'item_id' => $item_id,
                    'user_id' => Auth::id(), // 誰の注文か
                ],
            ]);
            $redirectUrl = $session->url;
        }

        session(['payment_method' => $request->payment_method]);
        return redirect($redirectUrl);
    }

    public function success(Request $request, $item_id)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('purchase.show', ['item_id' => $item_id])->with('error', '決済が完了していないか、セッションがタイムアウトしました。');
        }

        if (!app()->environment('testing')) {
            try {
                Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
                $session = Session::retrieve($sessionId);

                if ($session->payment_status !== 'paid') {
                    return redirect()->route('purchase.show', ['item_id' => $item_id])->with('error', '決済未完了');
                }
            } catch (\Exception $e) {
                return redirect()->route('purchase.show', ['item_id' => $item_id])->with('error', 'エラー');
            }
        }

        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $item->price, // セッションではなくアイテムの価格から取得
                'payment_method' => session('payment_method', 'カード支払い'),
                'shipping_postcode' => session('shipping_postcode', $user->postcode),
                'shipping_address' => session('shipping_address', $user->address),
                'shipping_building' => session('shipping_building', $user->building),
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item->id,
                'price' => $item->price,
            ]);

            $item->update(['trading_status' => '1']);
            DB::commit();

            session()->forget(['payment_method', 'shipping_postcode', 'shipping_address', 'shipping_building']);

            // ここでメッセージを付けてリダイレクト
            return redirect()->route('item.index')->with('status', '購入が完了しました！');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('item.index')->with('error', '決済は完了しましたが、注文情報の保存に失敗しました。');
        }
    }

    public function edit(Request $request, $item_id)
    {
        if ($request->filled('payment_method')) {
            session(['payment_method' => $request->payment_method]);
        }
        session(['is_editing_address' => true]);
        $item = Item::findOrFail($item_id);
        return view('item.purchase_address', compact('item'));
    }

    public function update(AddressRequest $request, $item_id)
    {

        $paymentMethod = $request->payment_method ?? session('payment_method');
        session([
            'payment_method' => $paymentMethod,
            'shipping_postcode' => $request->shipping_postcode,
            'shipping_address' => $request->shipping_address,
            'shipping_building' => $request->shipping_building,
            'is_editing_address' => true,
        ]);

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }

    public function webhook(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Webhook Error'], 400);
        }

        // 決済完了イベントのみを処理
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $itemId = $session->metadata->item_id ?? null;
            $userId = $session->metadata->user_id ?? null;
            $paymentMethodType = $session->payment_method_types[0] ?? 'card';
            $paymentMethodName = ($paymentMethodType === 'konbini') ? 'コンビニ支払い' : 'カード支払い';

            if (!$itemId || !$userId) {
                Log::error('Webhook Metadata missing: ' . json_encode($session->metadata));
                return response()->json(['error' => 'Metadata missing'], 400);
            }

            // 1. 重複処理防止: 既に注文が存在するかチェック
            $existingOrder = Order::where('user_id', $userId)
                ->whereHas('orderItems', function ($q) use ($itemId) {
                    $q->where('item_id', $itemId);
                })->first();

            if ($existingOrder) {
                return response()->json(['status' => 'already processed'], 200);
            }

            // 2. ユーザーと商品情報の取得
            $user = \App\Models\User::find($userId);
            $item = Item::find($itemId);

            if (!$user || !$item) {
                return response()->json(['error' => 'User or Item not found'], 404);
            }

            // 3. DB トランザクション処理
            DB::beginTransaction();
            try {
                $order = Order::create([
                    'user_id' => $user->id,
                    'total_price' => $item->price,
                    'payment_method' => $paymentMethodName, // 必要に応じてセッションやメタデータから取得
                    'shipping_postcode' => $user->postcode,
                    'shipping_address' => $user->address,
                    'shipping_building' => $user->building,
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'price' => $item->price,
                ]);

                $item->update(['trading_status' => '1']);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Database error'], 500);
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
