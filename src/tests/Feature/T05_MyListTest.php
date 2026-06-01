<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class T05_MyListTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;
    public function test_いいねした商品だけが表示される()
    {
        $myUser = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        $likedItem = Item::factory()->create(['name' => 'いいねした商品']);
        Item::factory()->create(['name' => 'いいねしていない商品']);

        $myUser->favorites()->attach($likedItem->id);

        $this->actingAs($myUser);
        $response = $this->get(route('item.index', ['tab' => 'mylist']));

        $response->assertStatus(200);

        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }

    public function test_購入済み商品はSoldと表示される()
    {
        $myUser = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        $soldItem = Item::factory()->create([
            'name' => 'いいねした売り切れのバッグ',
            'trading_status' => 1,
        ]);

        $myUser->favorites()->attach($soldItem->id);

        $this->actingAs($myUser);
        $response = $this->get(route('item.index', ['tab' => 'mylist']));

        $response->assertStatus(200);

        $response->assertSee('いいねした売り切れのバッグ');
        $response->assertSee('Sold');
    }

    public function test_未認証の場合は何も表示されない()
    {
        Item::factory()->create(['name' => 'ゲストには見えない商品']);

        $response = $this->get(route('item.index', ['tab' => 'mylist']));

        $response->assertStatus(200);

        $response->assertDontSee('ゲストには見えない商品');
    }
}
