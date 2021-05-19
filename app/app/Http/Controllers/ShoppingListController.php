<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShoppingListStoreRequest;
use App\Http\Resources\ShoppingListResource;
use App\Models\ShoppingList;
use App\Repositories\ShoppingListRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShoppingListController extends Controller
{
    protected ShoppingListRepository $shoppingListRepository;

    public function __construct(ShoppingListRepository $shoppingListRepository)
    {
        $this->shoppingListRepository = $shoppingListRepository;
    }

    /**
     * Returns a list of all existing items
     */
    public function index(Request $request): Response
    {
        if ($request->input('active')) {
            $shoppingLists = $this->shoppingListRepository->findActive();
        } else {
            $shoppingLists = $this->shoppingListRepository->all();
        }

        return new Response(
            ShoppingListResource::collection($shoppingLists)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShoppingListStoreRequest $request)
    {
        $newItem = $this->shoppingListRepository->create($request->input());
        return new Response([
            'message' => sprintf('Shopping list %1$s successfully created', $newItem['title']),
            'item' => new ShoppingListResource($newItem)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShoppingList $item): Response
    {
        return new Response(
            new ShoppingListResource($item)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShoppingListStoreRequest $request, ShoppingList $shoppingList): Response
    {
        $updatedModel = $this->shoppingListRepository->update($request->input(), $shoppingList);
        return new Response([
            'message' => sprintf('Shopping list %1$s successfully updated', $shoppingList),
            'item' => new ShoppingListResource($updatedModel)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ShoppingList $shoppingList)
    {
        $shoppingList->delete();
        return new Response(
            sprintf('Shopping list %1$s sucessfully deleted', $shoppingList)
        );
    }
}
