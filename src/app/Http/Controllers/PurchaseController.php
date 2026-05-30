<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);

        if ($item->trading_status === 1) {
            return redirect()->route('item.index');
        }

        $user = Auth::user();

        return view('item.purchase', compact('item', 'user'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $request->total_price,
                'payment_method' => $request->payment_method,
                'shipping_postcode' => $request->shipping_postcode,
                'shipping_address' => $request->shipping_address,
                'shipping_building' => $request->shipping_building,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item->id,
                'price' => $item->price,
            ]);

            $item->update(['trading_status' => '1']);
            DB::commit();

            session()->forget(['payment_method', 'shipping_postcode', 'shipping_address', 'shipping_building']);
            return redirect()->route('item.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return back();
        }
    }

    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('item.purchase_address', compact('item'));
    }

    public function update(AddressRequest $request, $item_id)
    {

        session([
            'payment_method' => $request->payment_method,
            'shipping_postcode' => $request->shipping_postcode,
            'shipping_address' => $request->shipping_address,
            'shipping_building' => $request->shipping_building,
        ]);

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }
}
