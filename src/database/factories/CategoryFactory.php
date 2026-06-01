<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Category::class;
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'ファッション',
                '家電',
                'インテリア',
                'レディース',
                'メンズ',
                'コスメ',
                '本',
                'ゲーム',
                'スポーツ',
                'キッチン',
                '食品',
                'ハンドメイド',
                'アクセサリー',
                'おもちゃ',
                'ベビー・キッズ',
            ]),
        ];
    }
}
