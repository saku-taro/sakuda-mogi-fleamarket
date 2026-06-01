<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

class T07_ItemDetailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;
    public function test_必要な情報が表示される()
    {
        $category = Category::factory()->create(['name' => 'ファッション']);

        $user = User::factory()->create([
            'name' => 'テスト 太郎',
            'profile_image' => 'profile/test.jpg',
        ]);

        $item = Item::factory()->create([
            'name' => 'テスト商品名',
            'brand_name' => 'テストブランド',
            'description' => 'テスト商品説明文',
            'price' => 1000,
            'status' => 1, // 目立った傷や汚れなし
        ]);

        $item->item_images()->create(['image_path' => 'item_images/test_item.jpg']);

        $item->categories()->attach($category->id);

        $item->comments()->create([
            'user_id' => $user->id,
            'body' => 'テストコメントです',
        ]);

        $item->favoritedBy()->attach($user->id);

        $response = $this->get(route('item.show', ['item_id' => $item->id]));

        $response->assertStatus(200);

        $response->assertSee('storage/item_images/test_item.jpg', false);
        $response->assertSee('テスト商品名');
        $response->assertSee('テストブランド');
        $response->assertSee('1,000');
        $response->assertSee('<span class="item-detail__like-count">1</span>', false);
        $response->assertSee('<span class="item-detail__comment-count">1</span>', false);

        $response->assertSee('テスト商品説明文');

        $response->assertSee('ファッション');
        $response->assertSee('目立った傷や汚れなし');

        $response->assertSee('コメント (1)');
        $response->assertSee('storage/profile/test.jpg', false);
        $response->assertSee('テスト 太郎');
        $response->assertSee('テストコメントです');
    }

    public function test_複数選択されたカテゴリが表示されているか()
    {
        $categories = Category::factory()->count(5)->create();

        $item = Item::factory()->create();

        $item->categories()->attach($categories->pluck('id')->toArray());

        $response = $this->get(route('item.show', ['item_id' => $item->id]));

        $response->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }
}
