<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class T09_CommentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_ログイン済みのユーザーはコメントを送信できる()
    {
        $user = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user);
        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertStatus(200);
        $response->assertSee('<span class="item-detail__comment-count">0</span>', false);

        $this->post(route('comment.store', ['item_id' => $item->id]), [
            'body' => 'テストコメントです',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'body' => 'テストコメントです',
        ]);

        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('<span class="item-detail__comment-count">1</span>', false);
        $response->assertSee('テストコメントです');
    }

    public function test_ログイン前のユーザーはコメントを送信できない()
    {
        $item = Item::factory()->create();

        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertStatus(200);
        $response->assertSee('<span class="item-detail__comment-count">0</span>', false);

        $response = $this->post(route('comment.store', ['item_id' => $item->id]), [
            'body' => 'ゲストコメントです',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'body' => 'ゲストコメントです',
        ]);

        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('<span class="item-detail__comment-count">0</span>', false);
        $response->assertDontSee('ゲストコメントです');
    }

    public function test_コメントが入力されていない場合のバリデーションメッセージ()
    {
        $user = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user);
        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $this->post(route('comment.store', ['item_id' => $item->id]), [
            'body' => '',
        ]);

        $this->assertDatabaseMissing('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'body' => '',
        ]);

        $response->assertSessionHasErrors(['body' => 'コメントを入力してください']);
    }

    public function test_コメントが255字以上の場合のバリデーションメッセージ()
    {
        $user = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        $item = Item::factory()->create();

        $longComment = str_repeat('a', 256);

        $this->actingAs($user);
        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $this->post(route('comment.store', ['item_id' => $item->id]), [
            'body' => $longComment,
        ]);

        $this->assertDatabaseMissing('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'body' => $longComment,
        ]);

        $response->assertSessionHasErrors(['body' => 'コメントは255文字以内で入力してください']);
    }
}
