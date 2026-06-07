<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class T10_PurchaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_購入するボタンを押下すると購入が完了する()
    {

        $user = User::factory()->create([
            'postcode' => '123-4567',
            'address' => 'テスト区',
            'building' => 'テストビル',
            'is_profile_completed' => true,
        ]);

        $item = Item::factory()->create([
            'trading_status' => 0,
            'price' => 1000,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'total_price' => $item->price,
            'payment_method' => 'コンビニ払い',
            'shipping_postcode' => $user->postcode,
            'shipping_address' => $user->address,
            'shipping_building' => $user->building,
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'trading_status' => 1,
        ]);

        $this->assertDatabaseHas('order_items', [
            'item_id' => $item->id,
            'price' => 1000,
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'shipping_postcode' => '123-4567',
            'shipping_address' => 'テスト区',
            'shipping_building' => 'テストビル',
            'total_price' => 1000,
        ]);
    }

    public function test_購入した商品は商品一覧画面にてsoldと表示される()
    {
        $user = User::factory()->create([
            'postcode' => '123-4567',
            'address' => 'テスト区',
            'building' => 'テストビル',
            'is_profile_completed' => true,
        ]);

        $item = Item::factory()->create([
            'name' => '売り切れのバッグ',
            'trading_status' => 0,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));
        $response->assertStatus(200);


        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'total_price'       => $item->price,
            'payment_method'    => 'コンビニ払い',
            'shipping_postcode' => $user->postcode,
            'shipping_address'  => $user->address,
            'shipping_building' => $user->building,
        ]);

        $this->assertDatabaseHas('items', [
            'id'             => $item->id,
            'name'           => '売り切れのバッグ',
            'trading_status' => 1,
        ]);

        $response = $this->get(route('item.index'));
        $response->assertStatus(200);
        $response->assertSee('売り切れのバッグ');
        $response->assertSee('Sold');
    }

    public function test_プロフィールの購入した商品一覧に追加されている()
    {
        $user = User::factory()->create([
            'postcode' => '123-4567',
            'address' => 'テスト区',
            'building' => 'テストビル',
            'is_profile_completed' => true,
        ]);

        $item = Item::factory()->create([
            'name' => '購入したバッグ',
            'trading_status' => 0,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));
        $response->assertStatus(200);


        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'total_price'       => $item->price,
            'payment_method'    => 'コンビニ払い',
            'shipping_postcode' => $user->postcode,
            'shipping_address'  => $user->address,
            'shipping_building' => $user->building,
        ]);

        $this->assertDatabaseHas('items', [
            'id'             => $item->id,
            'name'           => '購入したバッグ',
            'trading_status' => 1,
        ]);

        $response = $this->get(route('profile.show', ['page' => 'buy']));
        $response->assertStatus(200);
        $response->assertSee('購入したバッグ');
        $response->assertSee('Sold');
    }
}
