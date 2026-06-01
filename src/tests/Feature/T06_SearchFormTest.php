<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class T06_SearchFormTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_商品名で部分一致検索ができる()
    {
        Item::factory()->create(['name' => '高級な時計']);
        Item::factory()->create(['name' => '普通のバッグ']);

        $response = $this->get(route('item.index', ['keyword' => '時計']));

        $response->assertStatus(200);

        $response->assertSee('高級な時計');

        $response->assertDontSee('普通のバッグ');
    }

    public function test_検索状態がマイリストでも保持されている()
    {
        $myUser = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        $likedItem = Item::factory()->create(['name' => 'いいねした高級な時計']);
        Item::factory()->create(['name' => 'いいねしていない普通の時計']);
        $nonHitLikedItem = Item::factory()->create(['name' => 'いいねしたバッグ']);

        $myUser->favorites()->attach($likedItem->id);
        $myUser->favorites()->attach($nonHitLikedItem->id);

        $this->actingAs($myUser);
        $response = $this->get(route('item.index', ['keyword' => '時計']));

        $response->assertStatus(200);

        $response->assertSee('いいねした高級な時計');
        $response->assertSee('いいねしていない普通の時計');
        $response->assertDontSee('いいねしたバッグ');

        $response = $this->get(route('item.index', ['tab' => 'mylist', 'keyword' => '時計']));

        $response->assertStatus(200);

        $response->assertSee('いいねした高級な時計');
        $response->assertDontSee('いいねしていない普通の時計');
        $response->assertDontSee('いいねしたバッグ');
    }
}
