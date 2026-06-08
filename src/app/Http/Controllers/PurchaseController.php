<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

use Stripe\Stripe;
use Stripe\Checkout\Session;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    public function show(Request $request, $item_id)
    {
        $user = $request->user();
        $item = Item::findOrFail($item_id);

        if ($item->trading_status === 1) {
            return redirect()->route('item.index');
        }

        $isEditing = session()->pull('is_editing_address', false);

        if (!$isEditing && session('purchase_item_id') != $item_id) {
            session()->forget([
                'payment_method',
                'shipping_postcode',
                'shipping_address',
                'shipping_building',
            ]);
        }

        session(['purchase_item_id' => $item_id]);

        return view('item.purchase', compact('item', 'user'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        session(['payment_method' => $request->payment_method]);
        $user = $request->user();

        $order = DB::transaction(function () use ($user, $item_id) {
            $item = Item::where('id', $item_id)->lockForUpdate()->firstOrFail();

            if ($item->trading_status == '0') {
                $item->update(['trading_status' => '1']);

                $order = Order::create([
                    'user_id' => $user->id,
                    'total_price' => $item->price,
                    'payment_method' => session('payment_method'),
                    'shipping_postcode' => session('shipping_postcode', $user->postcode),
                    'shipping_address' => session('shipping_address', $user->address),
                    'shipping_building' => session('shipping_building', $user->building),
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'price' => $item->price,
                ]);
                return $order;
            }

            return null;
        });

        if (!$order) {
            return redirect()->route('items.index')->with('error', 'この商品はすでに売り切れています。');
        }

        $item = Item::findOrFail($item_id);
        session(['latest_order_id' => $order->id]);

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

            'cancel_url' => route('purchase.cancel', ['item_id' => $item_id]),
            'success_url' => route('purchase.success', ['item_id' => $item_id]),
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        session()->forget([
            'payment_method',
            'shipping_postcode',
            'shipping_address',
            'shipping_building',
            'latest_order_id'
        ]);
        return redirect()->route('item.index')->with('success', '購入が完了しました。');
    }

    public function cancel($item_id)
    {
        $orderId = session()->pull('latest_order_id');
        if ($orderId) {
            DB::transaction(function () use ($item_id, $orderId) {
                $item = Item::where('id', $item_id)->lockForUpdate()->firstOrFail();
                Order::destroy($orderId);
                $item->update(['trading_status' => '0']);
            });
        }
        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }

    public function edit(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        if ($request->has('payment_method')) {
            session(['payment_method' => $request->payment_method]);
        }
        session(['is_editing_address' => true]);
        return view('item.purchase_address', compact('item'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        $data = [
            'shipping_postcode' => $request->shipping_postcode,
            'shipping_address' => $request->shipping_address,
            'shipping_building' => $request->shipping_building,
            'is_editing_address' => true,
        ];

        if (session()->has('payment_method')) {
            $data['payment_method'] = session('payment_method');
        }

        session($data);

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }
}
