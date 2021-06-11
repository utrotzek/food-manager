<?php

namespace App\Http\Controllers;

use App\Http\Resources\GoodResource;
use App\Http\Resources\GoodResourceCollection;
use App\Models\Good;
use App\Repositories\GoodGroupRepository;
use App\Repositories\GoodRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, GoodGroupRepository $goodGroupRepository)
    {
        $response = [];
        $rules = [
            'title' => 'required|unique:goods|max:255',
            'carbs' => 'int',
            'fat' => 'int',
            'protein' => 'int',
            'kcal' => 'int',
            'piece_in_gramm' => 'int',
            'allow_in_recipes' => 'required|boolean',
            'good_group_id' => 'required|int|exists:good_groups,id'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            $statusCode = 500;
        } else {
            $group = $goodGroupRepository->findById($request->input('good_group_id'));
            $newItem = $this->goodRepository->create($request->input(), $group);
            $response['item'] = new GoodResource($newItem);
            $response['message'] = sprintf('Good %1$s successfully created', $newItem['title']);
            $statusCode = 200;
        }

        return new Response($response, $statusCode);
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
    public function update(Request $request, string $slugOrId, GoodGroupRepository $goodGroupRepository)
    {
        $response = [];
        $rules = [
            'title' => 'required|max:255',
            'carbs' => 'int',
            'fat' => 'int',
            'protein' => 'int',
            'kcal' => 'int',
            'piece_in_gramm' => 'int',
            'good_group_id' => 'required|int|exists:good_groups,id'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            $statusCode = 500;
        } else {
            $good = $this->goodRepository->findByIdOrSlug($slugOrId);
            $group = $goodGroupRepository->findById($request->input('good_group_id'));
            $newItem = $this->goodRepository->update($request->input(), $good, $group);
            $response['item'] = new GoodResource($newItem);
            $response['message'] = sprintf('Good %1$s successfully updated', $newItem['title']);
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
    public function destroy(string $slugOrId)
    {
        $good = $this->goodRepository->findByIdOrSlug($slugOrId);
        $good->delete();

        return new Response([
            'message' => sprintf('Good %1$s successfully deleted', $good['title'])
        ]);
    }
}
