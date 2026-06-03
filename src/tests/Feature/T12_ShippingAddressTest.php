<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class T12_ShippingAddressTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_送付先住所変更画面にて登録した住所が商品購入画面に反映されている()
    {
        $user = User::factory()->create([
            'postcode' => '123-4567',
            'address' => 'テスト区',
            'building' => 'テストビル',
            'is_profile_completed' => true,
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user);
        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));
        $response->assertStatus(200);
        $response->assertSee('123-4567');
        $response->assertSee('テスト区');
        $response->assertSee('テストビル');

        $response = $this->post(route('purchase.address.edit', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response = $this->post(route('purchase.address.update', ['item_id' => $item->id]), [
            'shipping_postcode' => '345-6789',
            'shipping_address' => 'test市',
            'shipping_building' => 'testハイツ',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('purchase.show', ['item_id' => $item->id]));

        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('345-6789');
        $response->assertSee('test市');
        $response->assertSee('testハイツ');
    }

    public function test_購入した商品に送付先住所が紐づいて登録される()
    {
        $user = User::factory()->create([
            'postcode' => '123-4567',
            'address' => 'テスト区',
            'building' => 'テストビル',
            'is_profile_completed' => true,
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user);
        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));
        $response->assertStatus(200);
        $response->assertSee('123-4567');
        $response->assertSee('テスト区');
        $response->assertSee('テストビル');

        $response = $this->post(route('purchase.address.edit', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response = $this->post(route('purchase.address.update', ['item_id' => $item->id]), [
            'shipping_postcode' => '345-6789',
            'shipping_address' => 'test市',
            'shipping_building' => 'testハイツ',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('purchase.show', ['item_id' => $item->id]));

        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('345-6789');
        $response->assertSee('test市');
        $response->assertSee('testハイツ');

        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'total_price'       => $item->price,
            'payment_method'    => 'コンビニ払い',
            'shipping_postcode' => '123-4567',
            'shipping_address'  => 'テスト区',
            'shipping_building' => 'テストビル',
        ]);

        $response->assertRedirect(route('purchase.success', ['item_id' => $item->id]));

        $response = $this->get(route('purchase.success', ['item_id' => $item->id]) . '?session_id=dummy_id');

        $response->assertStatus(302);

        $this->assertDatabaseHas('orders', [
            'user_id'           => $user->id,
            'shipping_postcode' => '345-6789',
            'shipping_address'  => 'test市',
            'shipping_building' => 'testハイツ',
        ]);
    }
}
