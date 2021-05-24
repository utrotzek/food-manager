<?php

use App\Http\Controllers\AppStateController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\DayPlanController;
use App\Http\Controllers\GoodController;
use App\Http\Controllers\GoodGroupController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\MealConfigController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ShoppingListController;
use App\Http\Controllers\ShoppingListItemController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/days/range', [DayController::class, 'range']);
Route::put('/days/{day}/shopping-day', [DayController::class, 'shoppingDay']);
Route::get('/day-plans/range', [DayPlanController::class, 'range']);
Route::put('/day-plans/{day_plan}/added-to-cart', [DayPlanController::class, 'addedToCart']);

Route::put('/goodGroups/resort/{goodGroup}', [GoodGroupController::class, 'resort']);

Route::post('/recipes/validate/{recipe?}', [RecipeController::class, 'validateRequest']);
Route::put('/recipes/flags/{recipe}', [RecipeController::class, 'flags']);
Route::get('/recipes/remembered', [RecipeController::class, 'remembered']);

Route::put('/shopping-list-items/move/{shopping_list_item}', [ShoppingListItemController::class, 'move']);
Route::post('/shopping-list-items/store-multiple', [ShoppingListItemController::class, 'storeMultiple']);

Route::apiResources([
    'app-states' => AppStateController::class,
    'days' => DayController::class,
    'day-plans' => DayPlanController::class,
    'goods' => GoodController::class,
    'goodGroups' => GoodGroupController::class,
    'ingredients' => IngredientController::class,
    'images' => ImageController::class,
    'meals' => MealController::class,
    'meal-config' => MealConfigController::class,
    'recipes' => RecipeController::class,
    'shopping-lists' => ShoppingListController::class,
    'shopping-list-items' => ShoppingListItemController::class,
    'tags' => TagController::class,
    'units' => UnitController::class,
]);

