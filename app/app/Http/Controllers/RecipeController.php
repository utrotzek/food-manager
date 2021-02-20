<?php

namespace App\Http\Controllers;

use App\Http\Resources\RecipeResource;
use App\Http\Resources\RecipeResourceCollection;
use App\Models\Recipe;
use App\Repositories\RecipeRepository;
use App\Repositories\StepRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    /**
     * @var RecipeRepository
     */
    protected RecipeRepository $recipeRepository;

    /**
     * @var StepRepository
     */
    protected StepRepository $stepRepository;

    public function __construct(RecipeRepository $recipeRepository, StepRepository $stepRepository)
    {
        $this->recipeRepository = $recipeRepository;
        $this->stepRepository = $stepRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new Response(
            new RecipeResourceCollection(
                $this->recipeRepository->all()
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = [];
        $rules = [
            'title' => 'required|max:255',
            'rating' => 'numeric',
            'portion' => 'required|int',
            'steps' => 'array'

        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            $statusCode = 500;
        } else {
            $newItem = $this->recipeRepository->create($request->input());

            if ($request->has('steps')) {
                $steps = $this->stepRepository->prepareByDescriptionArray($request->input('steps'));
                $newItem->steps()->saveMany($steps);
            }

            $response['item'] = new RecipeResource($newItem);
            $response['message'] = sprintf('Recipe %1$s successfully created', $newItem['title']);
            $statusCode = 200;
        }

        return new Response($response, $statusCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
        return new Response(
            new RecipeResource($recipe)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe)
    {
        $response = [];
        $rules = [
            'title' => 'required|max:255',
            'rating' => 'numeric',
            'portion' => 'required|int'

        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            $statusCode = 500;
        } else {
            $newItem = $this->recipeRepository->update($request->input(), $recipe);
            $response['item'] = new RecipeResource($newItem);
            $response['message'] = sprintf('Recipe %1$s successfully updated', $newItem['title']);
            $statusCode = 200;
        }

        return new Response($response, $statusCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        return new Response(
            ['message' => sprintf('Recipe %1$s successfully deleted', $recipe['title'])]
        );
    }
}
