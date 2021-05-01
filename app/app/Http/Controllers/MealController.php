<?php

namespace App\Http\Controllers;

use App\Http\Requests\MealStoreRequest;
use App\Http\Requests\MealUpdateRequest;
use App\Http\Resources\MealResource;
use App\Models\Meal;
use App\Repositories\MealConfigRepository;
use App\Repositories\MealRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MealController extends Controller
{
    protected MealRepository $mealRepository;

    public function __construct(MealRepository $mealRepository)
    {
        $this->mealRepository = $mealRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new Response(
            MealResource::collection($this->mealRepository->all())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MealStoreRequest $request, MealConfigRepository $mealConfigRepository)
    {
        $mealConfig = $mealConfigRepository->findByIdOrSlug($request->input('meal_config_id'));
        $newItem = $this->mealRepository->createForMealConfig($request->input(), $mealConfig);

        return new Response([
            'message' => sprintf('Meal %1$s successfully created', $newItem['title']),
            'item' => new MealResource($newItem)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Meal $item)
    {
        return new Response(
            new MealResource($item)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MealUpdateRequest $request, Meal $meal): Response
    {
        $updatedItem = $this->mealRepository->update($request->input(), $meal);
        return new Response([
            'message' => sprintf('Meal %1$s has successfully be updated', $meal),
            'item' => new MealResource($updatedItem)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Meal $meal)
    {
        $meal->delete();
        return new Response(
            sprintf('Meal %1$s sucessfully deleted', $meal)
        );
    }
}
