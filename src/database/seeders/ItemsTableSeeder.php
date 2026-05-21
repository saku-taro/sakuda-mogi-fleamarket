<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $item1 = Item::create([
            'user_id' => 1,
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => 15000,
            'status' => 0,
        ]);
        $item1->images()->create(['image_path' => 'item_images/mens-watch.jpg']);
        $item1->categories()->attach([1, 5, 12]);

        $item2 = Item::create([
            'user_id' => 1,
            'name' => 'HDD',
            'brand_name' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'price' => 5000,
            'status' => 1,
        ]);
        $item2->images()->create(['image_path' => 'item_images/HDD-harddisk.jpg']);
        $item2->categories()->attach([2]);

        $item3 = Item::create([
            'user_id' => 1,
            'name' => '玉ねぎ3束',
            'brand_name' => 'なし',
            'description' => '新鮮な玉ねぎ3束のセット',
            'price' => 300,
            'status' => 2,
        ]);
        $item3->images()->create(['image_path' => 'item_images/onion.jpg']);
        $item3->categories()->attach([]);

        $item4 = Item::create([
            'user_id' => 1,
            'name' => '革靴',
            'brand_name' => '',
            'description' => 'クラシックなデザインの革靴',
            'price' => 4000,
            'status' => 3,
        ]);
        $item4->images()->create(['image_path' => 'item_images/leather-shoes.jpg']);
        $item4->categories()->attach([1, 5]);

        $item5 = Item::create([
            'user_id' => 1,
            'name' => 'ノートPC',
            'brand_name' => '',
            'description' => '高性能なノートパソコン',
            'price' => 45000,
            'status' => 0,
        ]);
        $item5->images()->create(['image_path' => 'item_images/laptop.jpg']);
        $item5->categories()->attach([2]);

        $item6 = Item::create([
            'user_id' => 1,
            'name' => 'マイク',
            'brand_name' => 'なし',
            'description' => '高音質なマイク',
            'price' => 3000,
            'status' => 1,
        ]);
        $item6->images()->create(['image_path' => 'item_images/mic.jpg']);
        $item6->categories()->attach([2]);

        $item7 = Item::create([
            'user_id' => 1,
            'name' => 'ショルダーバッグ',
            'brand_name' => '',
            'description' => 'おしゃれなショルダーバッグ',
            'price' => 3500,
            'status' => 2,
        ]);
        $item7->images()->create(['image_path' => 'item_images/shoulder-bag.jpg']);
        $item7->categories()->attach([1, 4]);

        $item8 = Item::create([
            'user_id' => 1,
            'name' => 'タンブラー',
            'brand_name' => 'なし',
            'description' => '使いやすいタンブラー',
            'price' => 500,
            'status' => 3,
        ]);
        $item8->images()->create(['image_path' => 'item_images/tumbler.jpg']);
        $item8->categories()->attach([10]);

        $item9 = Item::create([
            'user_id' => 1,
            'name' => 'コーヒーミル',
            'brand_name' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'price' => 4000,
            'status' => 0,
        ]);
        $item9->images()->create(['image_path' => 'item_images/coffee-mill.jpg']);
        $item9->categories()->attach([10]);

        $item10 = Item::create([
            'user_id' => 1,
            'name' => 'メイクセット',
            'brand_name' => '',
            'description' => '便利なメイクアップセット',
            'price' => 2500,
            'status' => 1,
        ]);
        $item10->images()->create(['image_path' => 'item_images/makeup-tools.jpg']);
        $item10->categories()->attach([6]);
    }
}
