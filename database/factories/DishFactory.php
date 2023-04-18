<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Database\Seeders\DatabaseSeeder;
use Spatie\MediaLibrary\MediaCollections\Exceptions\UnreachableUrl;
use App\Models\Dish;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dish>
 */
class DishFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sort' => 0,
            'name' => $name = $this->faker->unique()->catchPhrase(),
            'description' => $this->faker->realText(),
            'visibility_restaurant_menu' => $this->faker->boolean(),
            'price' => $this->faker->randomFloat(2, 100, 2000),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }

    public function configure(): DishFactory
    {
        return $this->afterCreating(function (Dish $dish) {
            try {
                $dish
                    ->addMediaFromUrl(DatabaseSeeder::IMAGE_URL)
                    ->toMediaCollection('dishes-images');
            } catch (UnreachableUrl $exception) {
                return;
            }
        });
    }

    
}
