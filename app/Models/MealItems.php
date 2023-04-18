<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealItems extends Model
{
    use HasFactory;

    protected $table = 'meal_items';

    protected $fillable = [
        'sort',
        'meal_id',
        'dish_id',
        'separator',
        'extra_price'
    ];

}
