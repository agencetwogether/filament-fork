<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Database\Seeders\DatabaseSeeder;
use Spatie\MediaLibrary\MediaCollections\Exceptions\UnreachableUrl;
use App\Models\Meal;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $name = $this->faker->unique()->catchPhrase(),
            'slug' => Str::slug($name),
            'description' => $this->faker->realText(),
            'price' => $this->faker->randomFloat(2, 100, 2000),
            'notes' => $this->faker->realText(100),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }

    public function configure(): MealFactory
    {
        return $this->afterCreating(function (Meal $meal) {
            try {
                $meal
                    ->addMediaFromUrl(DatabaseSeeder::IMAGE_URL)
                    ->toMediaCollection('meals-images');
            } catch (UnreachableUrl $exception) {
                return;
            }
        });
    }

    
}
