<?php

namespace App\Http\Controllers;

use App\Http\Resources\GoodGroupResource;
use App\Http\Resources\GoodGroupResourceCollection;
use App\Models\GoodGroup;
use App\Models\GoodGroupRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class GoodGroupController extends Controller
{
    protected GoodGroupRepository $goodGroupRepository;

    public function __construct(GoodGroupRepository $goodGroupRepository)
    {
        $this->goodGroupRepository = $goodGroupRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        return new Response(
            new GoodGroupResourceCollection(
                GoodGroup::query()->orderBy('sort')->get()
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = [];
        $rules = [
            'title' => 'required|unique:good_groups|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['response'] = $validator->messages();
        } else {
            $title = $request->input('title');

            switch ($request->input('sort_type')) {
                case GoodGroupRepository::SORT_TYPYE_FIRST:
                    $goodGroup = $this->goodGroupRepository->createFirst($title);
                    break;
                case GoodGroupRepository::SORT_TYPYE_LAST:
                    $goodGroup = $this->goodGroupRepository->createLast($title);
                    break;
                case GoodGroupRepository::SORT_TYPYE_AFTER:
                    $goodGroup = $this->goodGroupRepository->createAfter($title, $request->input('after_sort'));
                    break;
            }

            $response = new Response([
               'message' => sprintf('Good %1$s successfully created', $goodGroup['title']),
               'item' => new GoodGroupResource($goodGroup)
            ]);
        }


        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(GoodGroup $goodGroup)
    {
        return new Response(
            new GoodGroupResource(
                $goodGroup
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GoodGroup $goodGroup)
    {
        $rules = [
            'title' => 'required|max:255'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['response'] = $validator->messages();
        } else {
            $goodGroup->update($request->all());

            $response = new Response(
                sprintf('Good %1$s successfully updated', $goodGroup['title'])
            );
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodGroup $goodGroup)
    {
        $goodGroup->delete();
        return new Response(
            sprintf('Good %1$s successfully deleted', $goodGroup['title'])
        );
    }
}
