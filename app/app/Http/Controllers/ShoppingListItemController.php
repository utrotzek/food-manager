<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShoppingListItemStoreRequest;
use App\Http\Resources\ShoppingListItemResource;
use App\Models\ShoppingListItem;
use App\Repositories\DayPlanRepository;
use App\Repositories\GoodRepository;
use App\Repositories\ShoppingListItemRepository;
use App\Repositories\ShoppingListRepository;
use App\Repositories\UnitRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShoppingListItemController extends Controller
{
    protected ShoppingListItemRepository $shoppingListItemRepository;
    protected GoodRepository $goodRepository;
    protected UnitRepository $unitRepository;
    protected ShoppingListRepository $shoppingListRepository;
    protected DayPlanRepository $dayPlanRepository;

    public function __construct(
        ShoppingListItemRepository $shoppingListItemRepository,
        GoodRepository $goodRepository,
        UnitRepository $unitRepository,
        ShoppingListRepository $shoppingListRepository,
        DayPlanRepository $dayPlanRepository
    ) {
        $this->shoppingListItemRepository = $shoppingListItemRepository;
        $this->unitRepository = $unitRepository;
        $this->goodRepository = $goodRepository;
        $this->shoppingListRepository = $shoppingListRepository;
        $this->dayPlanRepository = $dayPlanRepository;
    }

    /**
     * Returns a list of all existing items
     */
    public function index(Request $request): Response
    {
        $shoppingListId = $request->input('shopping_list_id');
        $shoppingLists = $this->shoppingListItemRepository->findByShoppingListId($shoppingListId);

        return new Response(
            ShoppingListItemResource::collection($shoppingLists)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShoppingListItemStoreRequest $request)
    {
        $good = $request->input('good_id') ? $this->goodRepository->findByIdOrSlug($request->input('good_id')) : null;
        $dayPlan = $request->input('day_plan_id') ? $this->dayPlanRepository->findById($request->input('day_plan_id')) : null;

        $unit = $request->input('unit_id') ? $this->unitRepository->findByIdOrSlug($request->input('unit_id')) : null;
        $shoppingList = $this->shoppingListRepository->findById($request->input('shopping_list_id'));

        $newItem = $this->shoppingListItemRepository->createForShoppingList(
            $shoppingList,
            $good,
            $unit,
            $dayPlan,
            $request->only(['descriptionAmount', 'description',  'unit_amount'])
        );
        return new Response([
            'message' => sprintf('Shopping list item "%1$s" successfully created', $newItem),
            'item' => new ShoppingListItemResource($newItem)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(): Response
    {
        throw new Exception('Not yet implemented');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShoppingListItemStoreRequest $request, ShoppingListItem $shoppingListItem): Response
    {
        $good = $request->input('good_id') ? $this->goodRepository->findByIdOrSlug($request->input('good_id')) : null;
        $dayPlan = $request->input('day_plan_id') ? $this->dayPlanRepository->findById($request->input('day_plan_id')) : null;
        $unit = $request->input('unit_id') ? $this->unitRepository->findByIdOrSlug($request->input('unit_id')) : null;

        $shoppingList = $this->shoppingListRepository->findById($request->input('shopping_list_id'));

        $updatedItem = $this->shoppingListItemRepository->updateForShoppingList(
            $shoppingListItem,
            $shoppingList,
            $good,
            $unit,
            $dayPlan,
            $request->only(['descriptionAmount', 'description', 'unit_amount'])
        );

        return new Response([
            'message' => sprintf('Shopping list "%1$s" successfully updated', $updatedItem),
            'item' => new ShoppingListItemResource($updatedItem)
        ]);
    }

    public function move(Request $request, ShoppingListItem $shoppingListItem): Response
    {
        $shoppingList = $this->shoppingListRepository->findById($request->input('shopping_list_id'));
        $updatedItem = $this->shoppingListItemRepository->moveToShoppingList($shoppingListItem, $shoppingList);
        return new Response([
            'message' => sprintf('Shopping list "%1$s" successfully moved', $updatedItem),
            'item' => new ShoppingListItemResource($updatedItem)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ShoppingListItem $shoppingListItem)
    {
        $shoppingListItem->delete();
        return new Response(
            sprintf('Shopping list item "%1$s" sucessfully deleted', $shoppingListItem)
        );
    }
}
