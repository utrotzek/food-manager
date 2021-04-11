<?php

namespace App\Http\Controllers;

use App\Http\Requests\DayPlanStoreRequest;
use App\Http\Resources\DayPlanResource;
use App\Models\DayPlan;
use App\Repositories\DayPlanRepository;
use App\Repositories\DayRepository;
use App\Repositories\MealRepository;
use App\Repositories\RecipeRepository;
use Illuminate\Http\Response;

class DayPlanController extends Controller
{
    protected DayPlanRepository $dayPlanRepository;
    protected MealRepository $mealRepository;
    protected RecipeRepository $recipeRepository;
    protected DayRepository $dayRepository;

    public function __construct(
        DayPlanRepository $dayPlanRepository,
        DayRepository $dayRepository,
        RecipeRepository $recipeRepository,
        MealRepository $mealRepository
    ) {
        $this->dayPlanRepository = $dayPlanRepository;
        $this->dayRepository = $dayRepository;
        $this->mealRepository = $mealRepository;
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new Response(
            DayPlanResource::collection($this->dayPlanRepository->all())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DayPlanStoreRequest $request)
    {
        $recipe = $this->recipeRepository->findByIdOrSlug($request['recipe_id']);
        $meal = $this->mealRepository->findById($request['meal_id']);
        $day = $this->dayRepository->findByIdOrDate($request['day_id']);

        $dayPlan = $this->dayPlanRepository->createForDay($day, $recipe, $meal, $request->input());

        return new Response([
            'message' => sprintf(
                'Day plan "%1$s" for date "%2$s" successfully created',
                $dayPlan,
                $day
            ),
            'item' => new DayPlanResource($dayPlan)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return new Response(
            "Fetching a single record is not implemented",
            501
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DayPlanStoreRequest $request, DayPlan $dayPlan): Response
    {
        $recipe = $this->recipeRepository->findByIdOrSlug($request['recipe_id']);
        $meal = $this->mealRepository->findById($request['meal_id']);
        $day = $this->dayRepository->findByIdOrDate($request['day_id']);

        $dayPlan = $this->dayPlanRepository->updateForDay(
            $dayPlan,
            $day,
            $recipe,
            $meal,
            $request->input()
        );

        return new Response([
            'message' => sprintf(
                'Plan "%1$s" for day "%2$s" has successfully been updated',
                $dayPlan,
                $day
            ),
            'item' => new DayPlanResource($dayPlan)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DayPlan $dayPlan)
    {
        $day = $dayPlan->day;
        $dayPlan->delete();
        return new Response(
            sprintf(
                'Day plan "%1$s" for day "%2$s" sucessfully deleted',
                $dayPlan,
                $day
            )
        );
    }
}
