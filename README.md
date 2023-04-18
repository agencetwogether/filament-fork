# SpatieMediaLibraryFileUpload inside createOptionForm()

I have an exception when I save record with SpatieMediaLibraryFileUpload inside createOptionForm 
` Call to undefined method App\Models\MealItems::getMedia() `

# Packages Versions

laravel/framework v10.7.1
filament/filament v2.17.25
filament/spatie-laravel-media-library-plugin v2.17.25

PHP v8.1

## Problem description

In a modal to create new Dish, in create context, no error showed and no media saved. In edit context, errors occurs.
See below ...

## Expected behavior

Media save as expected.

## Set project

 1. Clone this repo
 2. `composer install`
 3. Rename .env.example to .env file to set your database
 4. Run 
	 - `php artisan migrate`
	 - `php artisan db:seed`
	 - `php artisan  storage:link` 
	 - `php artisan key:generate`
	 - `php artisan serve`
 6. Go to admin panel at `http://127.0.0.1:8000/admin` and log in with credentials : *admin@admin.lab* // *password* .

## Steps to reproduce

In **Dishes** item, you can create and edit a dish.
Pay your attention to ***SpatieMediaLibraryFileUpload*** component.
With implements of `HasMedia`in Dish Model, image saves in public folder and in media table as expected.

Now go to **Meals** item, create a new Meal.
Fill required fields, also an image. Then in repeater field, fill fields and choose a dish in select component. Save works normally. Image recorded in media table and in public folder. Meal model has also implements `HasMedia`. 

 - **Issue 1**

Create a new Meal, but now create a new Dish by clicking on ***+*** button near select component Dish id.

A modal opens with form to create a Dish.
Fill fields ans choose an image.
After clicking on Create button, this new Dish is selected in select component, but now, no record in media table and so no in public folder.

 - **Issue 2**

Now, edit a Meal.
Form is filled as expected. Go to repeater field and click on ***+*** button near select component Dish id , modal opens and fill fields. Don't forget to upload image in ***SpatieMediaLibraryFileUpload*** component.
When you hit Create button, error `Call to undefined method App\Models\MealItems::getMedia()` occurs.

The media must be attached to the Dish model and not to the MealItems model. 
Table meal_items (and so MealItems model) is used to save hasMany relation of Meal model.


If you want more details please ask me.

Thank you


# Reproduction repository

[https://github.com/agencetwogether/filament-fork](https://github.com/agencetwogether/filament-fork)