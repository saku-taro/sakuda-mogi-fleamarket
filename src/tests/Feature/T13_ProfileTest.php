<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;


class T13_ProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）()
    {
        $user = User::factory()->create([
            'name' => 'テスト 太郎',
            'postcode' => '123-4567',
            'address' => 'テスト区',
            'building' => 'テストビル',
            'profile_image' => 'profile/test.jpg',
            'is_profile_completed' => true,
        ]);

        Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品した時計',
        ]);

        $buyItem = Item::factory()->create([
            'name' => '購入したバッグ',
            'trading_status' => 1,
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $buyItem->price,
            'payment_method' => 'クレジットカード',
            'shipping_postcode' => $user->postcode,
            'shipping_address' => $user->address,
            'shipping_building' => $user->building,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'item_id' => $buyItem->id,
            'price' => $buyItem->price,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('profile.show'));
        $response->assertStatus(200);
        $response->assertSee('storage/profile/test.jpg', false);
        $response->assertSee('テスト 太郎');

        $response = $this->get(route('profile.show', ['page' => 'sell']));
        $response->assertStatus(200);
        $response->assertSee('出品した時計');

        $response = $this->get(route('profile.show', ['page' => 'buy']));
        $response->assertStatus(200);
        $response->assertSee('購入したバッグ');
    }
}
