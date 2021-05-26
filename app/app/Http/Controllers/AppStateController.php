<?php
namespace App\Http\Controllers;

use App\Http\Requests\AppStateStoreRequest;
use App\Http\Resources\AppStateResource;
use App\Models\AppState;
use App\Repositories\AppStateRepository;
use Illuminate\Http\Response;

class AppStateController extends Controller
{
    protected AppStateRepository $appStateRepository;

    public function __construct(AppStateRepository $appStateRepository)
    {
        $this->appStateRepository = $appStateRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return new Response(
            AppStateResource::collection($this->appStateRepository->all())
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): Response
    {
        return new Response(['Creating new app states is not implemented'], 501);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AppState  $appState)
    {
        return new Response(
            new AppStateResource($appState)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AppStateStoreRequest $request, AppState $item): Response
    {
        $updatedItem = $this->appStateRepository->update($request->input('value'), $item);
        return new Response([
            'message' => sprintf('App state "%1$s" has successfully been updated', $item),
            'item' => new AppStateResource($updatedItem)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppState $appState): Response
    {
        return new Response(['Deleting app states is not possiblee'], 501);
    }
}
