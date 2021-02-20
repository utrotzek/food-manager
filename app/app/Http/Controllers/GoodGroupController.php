<?php

namespace App\Http\Controllers;

use App\Http\Resources\GoodGroupResource;
use App\Http\Resources\GoodGroupResourceCollection;
use App\Models\GoodGroup;
use App\Repositories\GoodGroupRepository;
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
                case GoodGroupRepository::SORT_TYPE_FIRST:
                    $goodGroup = $this->goodGroupRepository->createFirst($title);
                    break;
                case GoodGroupRepository::SORT_TYPE_LAST:
                    $goodGroup = $this->goodGroupRepository->createLast($title);
                    break;
                case GoodGroupRepository::SORT_TYPE_AFTER:
                    $sortAfterItem = $this->goodGroupRepository->findById($request->input('after_sort_id'));
                    $goodGroup = $this->goodGroupRepository->createAfter($title, $sortAfterItem);
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
        $rules = ['title' => 'required|unique:good_groups|max:255'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['response'] = $validator->messages();
        } else {
            $title = $request->input('title');
            $goodGroup = $this->goodGroupRepository->updateTitle($title, $goodGroup);

            $response = sprintf('Good %1$s successfully updated', $goodGroup['title']);
        }
        return new Response($response);
    }

    public function resort(Request $request, GoodGroup $goodGroup)
    {
        $rules = ['sort_type' => 'required'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['response'] = $validator->messages();
        } else {
            switch ($request->input('sort_type')) {
                case GoodGroupRepository::SORT_TYPE_FIRST:
                    $goodGroup = $this->goodGroupRepository->resortFirst($goodGroup);
                    $response = sprintf('Good %1$s successfully sorted first', $goodGroup['title']);
                    break;
                case GoodGroupRepository::SORT_TYPE_LAST:
                    $goodGroup = $this->goodGroupRepository->resortLast($goodGroup);
                    $response = sprintf('Good %1$s successfully sorted last', $goodGroup['title']);
                    break;
                case GoodGroupRepository::SORT_TYPE_AFTER:
                    $rules = ['after_sort_id' => 'required|int'];
                    $validator = Validator::make($request->all(), $rules);

                    if ($validator->fails()) {
                        $response['response'] = $validator->messages();
                    } else {
                        $goodGroupAfter =  $this->goodGroupRepository->findById($request->input('after_sort_id'));
                        $this->goodGroupRepository->resortAfter($goodGroup, $goodGroupAfter);
                        $response = sprintf(
                            'Good %1$s successfully sorted after %2$s',
                            $goodGroup['title'],
                            $goodGroupAfter['title']
                        );
                    }
                    break;
            }
        }
        return new Response($response);
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
