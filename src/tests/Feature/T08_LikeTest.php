<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class T08_LikeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_いいねアイコンを押すといいねした商品として登録できる()
    {
        $user = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user);
        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertStatus(200);
        $response->assertSee('<span class="item-detail__like-count">0</span>', false);

        $this->post(route('like.store', ['item_id' => $item->id]));

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('<span class="item-detail__like-count">1</span>', false);
    }

    public function test_追加済みのアイコンは色が変化する()
    {
        $user = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user);
        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertStatus(200);


        $response->assertSee('item-detail__like-icon--off', false);
        $response->assertDontSee('item-detail__like-icon--on', false);

        $this->post(route('like.store', ['item_id' => $item->id]));

        $user->refresh();

        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertStatus(200);


        $response->assertSee('item-detail__like-icon--on', false);
        $response->assertDontSee('item-detail__like-icon--off', false);
    }

    public function test_再度いいねを押すと解除できる()
    {
        $user = User::factory()->create([
            'is_profile_completed' => true,
        ]);
        $item = Item::factory()->create();

        $item->favoritedBy()->attach($user->id);


        $this->actingAs($user);
        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('item-detail__like-icon--on', false);
        $response->assertDontSee('item-detail__like-icon--off', false);

        $this->delete(route('like.destroy', ['item_id' => $item->id]));

        $user->refresh();

        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('item-detail__like-icon--off', false);
        $response->assertDontSee('item-detail__like-icon--on', false);
    }
}
