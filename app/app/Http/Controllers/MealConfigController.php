<?php

namespace App\Http\Controllers;

use App\Http\Requests\MealConfigStoreRequest;
use App\Http\Resources\MealConfigResource;
use App\Models\MealConfig;
use App\Repositories\MealConfigRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MealConfigController extends Controller
{
    protected MealConfigRepository $mealConfigRepository;

    public function __construct(MealConfigRepository $dayRepository)
    {
        $this->mealConfigRepository = $dayRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new Response(
            MealConfigResource::collection($this->mealConfigRepository->all())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MealConfigStoreRequest $request)
    {
        $newItem = $this->mealConfigRepository->create($request->input());
        return new Response([
            'message' => sprintf('Meal config %1$s successfully created', $newItem['title']),
            'item' => new MealConfigResource($newItem)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MealConfig $item)
    {
        return new Response(
            new MealConfigResource($item)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MealConfigStoreRequest $request, MealConfig $item): Response
    {
        $updatedItem = $this->mealConfigRepository->update($request->input(), $item);
        return new Response([
            'message' => sprintf('Day %1$s has successfully be updated', $item),
            'item' => new MealConfigResource($updatedItem)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MealConfig $mealConfig)
    {
        $mealConfig->delete();
        return new Response(
            sprintf('Meal config %1$s sucessfully deleted', $mealConfig['title'])
        );
    }
}
