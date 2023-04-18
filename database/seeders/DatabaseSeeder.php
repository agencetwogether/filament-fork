<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Dish;
use App\Models\Meal;
use App\Models\MealItems;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
class DatabaseSeeder extends Seeder
{
    const IMAGE_URL = 'https://source.unsplash.com/random/200x200/?img=1';

    public function run(): void
    {
        // Clear images
        Storage::deleteDirectory('public');

        //$user = User::factory()->create();

        
        $categories = Category::factory()->count(3)->create();

        $dishes = Dish::factory()->count(6)
            ->sequence(fn ($sequence) => ['category_id' => $categories->random(1)->first()->id])
            ->create();

        $meals = Meal::factory()->count(1)
            ->has(
                MealItems::factory()->count(rand(2, 5))
                    ->state(fn (array $attributes, Meal $meal) => ['dish_id' => $dishes->random(1)->first()->id]),
                'items'
            )
            ->create();
    }
}
