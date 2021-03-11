<?php

namespace App\Http\Controllers;

use App\Factories\IngredientFactory;
use App\Http\Requests\RecipeStoreRequest;
use App\Http\Resources\RecipeLightResource;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Repositories\IngredientRepository;
use App\Repositories\RecipeRepository;
use App\Repositories\StepRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    /**
     * @var RecipeRepository
     */
    protected RecipeRepository $recipeRepository;

    /**
     * @var IngredientRepository
     */
    protected IngredientRepository $ingredientRepository;

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
        IngredientFactory $ingredientFactory,
        IngredientRepository $ingredientRepository
    ) {
        $this->recipeRepository = $recipeRepository;
        $this->stepRepository = $stepRepository;
        $this->ingredientFactory = $ingredientFactory;
        $this->ingredientRepository = $ingredientRepository;
    }

    /**
     * Validates the given request and nothing more
     * @param RecipeStoreRequest $request
     */
    public function validateRequest(RecipeStoreRequest $request): void
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        return new Response(
            RecipeLightResource::collection(
                $this->recipeRepository->searchPaginated($request->input('searchTerm'))
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecipeStoreRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $newItem = $this->recipeRepository->create($request->input());

                if ($request->has('ingredients')) {
                    $ingredients = $this->ingredientFactory->createIngredientList(
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
                return new Response($response);
            });
        } catch (\Throwable $e) {
            return new Response('Recipe could not be created', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
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
    public function update(RecipeStoreRequest $request, Recipe $recipe)
    {
        $newItem = $this->recipeRepository->update($request->input(), $recipe);
        $actualSteps = $this->stepRepository->syncStepsWithDescriptionList(
            $newItem->steps()->get(),
            $request->input('steps')
        );
        $newItem->steps()->saveMany($actualSteps);
        $newItem->tags()->sync($request->input('tags'));
        $actualIngredients = $this->ingredientFactory->createIngredientList($request->input('ingredients'));
        $this->ingredientRepository->deleteRemovedItems($newItem->ingredients()->get(), $actualIngredients);
        $newItem->ingredients()->saveMany($actualIngredients);

        $response['item'] = new RecipeResource($newItem->fresh());
        $response['message'] = sprintf('Recipe %1$s successfully updated', $newItem['title']);
        return new Response($response);
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
