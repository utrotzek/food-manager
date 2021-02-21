<?php

namespace App\Http\Controllers;

use App\Factories\IngredientFactory;
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
     * @var IngredientFactory
     */
    protected IngredientFactory  $ingredientFactory;

    /**
     * @var StepRepository
     */
    protected StepRepository $stepRepository;

    public function __construct(
        RecipeRepository $recipeRepository,
        StepRepository $stepRepository,
        IngredientFactory $ingredientFactory
    ) {
        $this->recipeRepository = $recipeRepository;
        $this->stepRepository = $stepRepository;
        $this->ingredientFactory = $ingredientFactory;
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
            'title' => 'required|unique:recipes|max:255',
            'rating' => 'numeric',
            'portion' => 'required|int',
            'steps' => 'array',
            'tags' => 'array',
            'ingredients.unitId' => 'int',
            'ingredients.goodId' => 'int',
            'ingredients.amount' => 'int',

        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            $statusCode = 500;
        } else {
            $newItem = $this->recipeRepository->create($request->input());

            if ($request->has('ingredients')) {
                $ingredients = $this->ingredientFactory->newIngredientList(
                    $request->input('ingredients')
                );
                $newItem->ingredients()->saveMany($ingredients);
            }

            if ($request->has('steps')) {
                $steps = $this->stepRepository->prepareByDescriptionArray($request->input('steps'));
                $newItem->steps()->saveMany($steps);
            }
            $newItem->tags()->sync($request->input('tags'));

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
    public function show(string $slugOrId)
    {
        $recipe = $this->recipeRepository->findByIdOrSlug($slugOrId);

        if ($recipe) {
            return new Response(
                new RecipeResource($recipe)
            );
        } else {
            return new Response(
                sprintf('recipe "%1$s" could not be found.', $slugOrId),
                404
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $slugOrId)
    {
        $response = [];
        $rules = [
            'title' => 'required|max:255',
            'rating' => 'numeric',
            'portion' => 'required|int',
            'steps' => 'array',
            'tags' => 'array',

        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            $statusCode = 500;
        } else {
            $recipe = $this->recipeRepository->findByIdOrSlug($slugOrId);

            if ($recipe) {
                $newItem = $this->recipeRepository->update($request->input(), $recipe);
                $actualSteps = $this->stepRepository->syncStepsWithDescriptionList(
                    $newItem->steps()->get(),
                    $request->input('steps')
                );
                $newItem->steps()->saveMany($actualSteps);
                $newItem->tags()->sync($request->input('tags'));

                $response['item'] = new RecipeResource($newItem->fresh());
                $response['message'] = sprintf('Recipe %1$s successfully updated', $newItem['title']);
                $statusCode = 200;
            } else {
                $response['message'] = sprintf('recipe "%1$s" could not be found.', $slugOrId);
                $statusCode = 404;
            }
        }

        return new Response($response, $statusCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $slugOrId)
    {
        $recipe = $this->recipeRepository->findByIdOrSlug($slugOrId);
        $recipe->delete();

        return new Response(
            ['message' => sprintf('Recipe %1$s successfully deleted', $recipe['title'])]
        );
    }
}
