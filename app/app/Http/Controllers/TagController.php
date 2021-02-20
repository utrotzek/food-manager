<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Http\Resources\TagResourceCollection;
use App\Http\Resources\UnitResource;
use App\Models\Tag;
use App\Repositories\TagRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    protected TagRepository $tagRespository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRespository = $tagRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new Response(
            new TagResourceCollection(
                $this->tagRespository->all()
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
            'title' => 'required|unique:tags|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            $statusCode = 500;
        } else {
            $newItem = $this->tagRespository->create($request->input());
            $response['item'] = new TagResource($newItem);
            $response['message'] = sprintf('Tag %1$s successfully created', $newItem['title']);
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
    public function show(Tag $tag)
    {
        return new Response(
            new TagResource($tag)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $response = [];
        $rules = [
            'title' => 'required|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            $statusCode = 500;
        } else {
            $newItem = $this->tagRespository->update($request->input(), $tag);
            $response['item'] = new TagResource($newItem);
            $response['message'] = sprintf('Tag %1$s successfully updated', $newItem['title']);
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
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return new Response(
            ['message' => sprintf('Tag %1$s successfully deleted', $tag['title'])]
        );
    }
}
