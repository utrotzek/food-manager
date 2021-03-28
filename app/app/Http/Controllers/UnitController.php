<?php

namespace App\Http\Controllers;

use App\Http\Resources\UnitResource;
use App\Http\Resources\UnitResourceCollection;
use App\Repositories\UnitRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * @var UnitRepository
     */
    protected UnitRepository $unitRepository;

    public function __construct(UnitRepository $unitRepository)
    {
        $this->unitRepository = $unitRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new Response(
            new UnitResourceCollection(
                $this->unitRepository->all()
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
            'title' => 'required|unique:units|max:255',
            'is_piece' => 'boolean',
            'average_gram' => 'int',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            $statusCode = 500;
        } else {
            $newItem = $this->unitRepository->create($request->input());
            $response['item'] = new UnitResource($newItem);
            $response['message'] = sprintf('Unit %1$s successfully created', $newItem['title']);
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
        $unit = $this->unitRepository->findByIdOrSlug($slugOrId);
        return new Response(
            new UnitResource($unit)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slugOrId)
    {
        $unit = $this->unitRepository->findByIdOrSlug($slugOrId);
        $response = [];
        $rules = [
            'title' => 'required|max:255',
            'is_piece' => 'boolean',
            'average_gram' => 'int',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            $statusCode = 500;
        } else {
            $newItem = $this->unitRepository->update($request->input(), $unit);
            $response['item'] = new UnitResource($newItem);
            $response['message'] = sprintf('Unit %1$s successfully updated', $newItem['title']);
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
        $unit = $this->unitRepository->findByIdOrSlug($slugOrId);
        $unit->delete();

        return new Response(
            ['message' => sprintf('Unit %1$s successfully deleted', $unit['title'])]
        );
    }
}
