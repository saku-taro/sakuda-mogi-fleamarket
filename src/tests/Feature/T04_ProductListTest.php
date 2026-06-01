<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class T04_ProductListTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;
    public function test_全商品データの取得()
    {

        Item::factory()->count(5)->create();

        $totalCount = Item::count();

        $response = $this->get(route('item.index'));

        $response->assertStatus(200);

        $response->assertViewHas('allItems', function ($items) use ($totalCount) {
            return $items->count() === $totalCount;
        });
    }

    public function test_購入済み商品はSoldと表示される()
    {

        Item::factory()->create([
            'name' => '売り切れのバッグ',
            'trading_status' => 1,
        ]);

        $response = $this->get(route('item.index'));

        $response->assertStatus(200);

        $response->assertSee('売り切れのバッグ');
        $response->assertSee('Sold');
    }

    public function test_自分が出品した商品は表示されない()
    {
        $myUser = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        $otherUser = User::factory()->create();

        Item::factory()->create([
            'user_id' => $myUser->id,
            'name' => '自分の商品'
        ]);

        Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '他人の商品'
        ]);

        $this->actingAs($myUser);
        $response = $this->get(route('item.index'));

        $response->assertStatus(200);

        $response->assertSee('他人の商品');

        $response->assertDontSee('自分の商品');
    }
}
