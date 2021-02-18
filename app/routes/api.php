<?php
use App\Http\Controllers\GoodController;
use App\Http\Controllers\GoodGroupController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\StepController;
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

Route::apiResources([
    'goods' => GoodController::class,
    'goodGroups' => GoodGroupController::class,
    'ingredients' => IngredientController::class,
    'recipes' => RecipeController::class,
    'steps' => StepController::class,
    'tags' => TagController::class,
    'units' => UnitController::class
]);


Route::put('/goodGroups/resort/{goodGroup}', [GoodGroupController::class, 'resort']);
