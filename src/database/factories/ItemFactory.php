<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\User;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Item::class;
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word,
            'brand_name' => $this->faker->company,
            'description' => $this->faker->text(100),
            'price' => $this->faker->numberBetween(1000, 50000),
            'status' => $this->faker->randomElement([0, 1, 2, 3]),
            'trading_status' => 0,
        ];
    }
}
