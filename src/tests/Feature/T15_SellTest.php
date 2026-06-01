<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

class T15_SellTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、ブランド名、商品の説明、販売価格）()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'is_profile_completed' => true,
        ]);

        $categories = Category::factory()->count(3)->create();
        $categoryIds = $categories->pluck('id')->toArray();

        $file = UploadedFile::fake()->create('item.jpg', 500, 'image/jpeg');

        $this->actingAs($user);
        $response = $this->get(route('item.create'));
        $response->assertStatus(200);

        $response = $this->post(route('item.store'), [
            'item_image' => $file,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 1000,
            'status' => 1,
            'category_ids' => $categoryIds
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $item = Item::with('item_images')->latest()->first();
        $imagePath = $item->item_images->first()->image_path;

        Storage::disk('public')->assertExists($imagePath);
        $this->assertStringStartsWith('item_images/', $imagePath);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 1000,
            'status' => 1
        ]);

        foreach ($categoryIds as $categoryId) {
            $this->assertDatabaseHas('category_item', [
                'item_id' => $item->id,
                'category_id' => $categoryId,
            ]);
        }
    }
}
