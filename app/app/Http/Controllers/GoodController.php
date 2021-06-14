<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoodStoreRequest;
use App\Http\Resources\GoodResource;
use App\Http\Resources\GoodResourceCollection;
use App\Models\Good;
use App\Repositories\GoodGroupRepository;
use App\Repositories\GoodRepository;
use Illuminate\Http\Response;

class GoodController extends Controller
{
    protected GoodRepository $goodRepository;

    public function __construct(GoodRepository $goodRepository)
    {
        $this->goodRepository = $goodRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        return new Response(new GoodResourceCollection($this->goodRepository->all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GoodStoreRequest $request, GoodGroupRepository $goodGroupRepository)
    {
        $response = [];
        $group = $goodGroupRepository->findById($request->input('good_group_id'));
        $newItem = $this->goodRepository->create($request->input(), $group);
        $response['item'] = new GoodResource($newItem);
        $response['message'] = sprintf('Good %1$s successfully created', $newItem['title']);
        return new Response($response);
    }

    /**
     * Return a certain good
     */
    public function show(string $slugOrId): Response
    {
        $good = $this->goodRepository->findByIdOrSlug($slugOrId);
        return new Response(new GoodResource($good));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GoodStoreRequest $request, Good $good, GoodGroupRepository $goodGroupRepository)
    {
        $response = [];
        $group = $goodGroupRepository->findById($request->input('good_group_id'));
        $newItem = $this->goodRepository->update($request->input(), $good, $group);
        $response['item'] = new GoodResource($newItem);
        $response['message'] = sprintf('Good %1$s successfully updated', $newItem['title']);
        return new Response($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Good $good)
    {
        $good->delete();
        return new Response([
            'message' => sprintf('Good %1$s successfully deleted', $good['title'])
        ]);
    }
}
