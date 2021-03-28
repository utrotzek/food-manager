<?php
use App\Http\Controllers\GoodController;
use App\Http\Controllers\GoodGroupController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
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

Route::post('/recipes/validate/{recipe?}', [RecipeController::class, 'validateRequest']);
Route::put('/recipes/flags/{recipe}', [RecipeController::class, 'flags']);
Route::get('/recipes/remembered', [RecipeController::class, 'remembered']);

Route::apiResources([
    'goods' => GoodController::class,
    'goodGroups' => GoodGroupController::class,
    'ingredients' => IngredientController::class,
    'recipes' => RecipeController::class,
    'tags' => TagController::class,
    'units' => UnitController::class,
    'images' => ImageController::class
]);

Route::put('/goodGroups/resort/{goodGroup}', [GoodGroupController::class, 'resort']);
