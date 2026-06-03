<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class T11_PaymentMethodTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_小計画面で変更が反映される()
    {
        $user = User::factory()->create([
            'is_profile_completed' => true,
        ]);
        $item = Item::factory()->create();

        $this->actingAs($user);
        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response = $this->post(route('purchase.address.edit', ['item_id' => $item->id]), [
            'payment_method' => 'コンビニ払い',
        ]);
        $response->assertStatus(200);

        $response = $this->post(route('purchase.address.update', ['item_id' => $item->id]), [
            'shipping_postcode' => $user->postcode,
            'shipping_address' => $user->address,
        ]);
        $response->assertStatus(302);

        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));
        $response->assertSee('<span class="purchase-summary__value" id="display-payment">コンビニ払い</span>', false);
    }
}
