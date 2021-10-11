<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarStoreRequest;
use App\Http\Resources\CalendarResource;
use App\Http\Resources\CalendarResourceCollection;
use App\Interfaces\RepositoryInterfaces\CalendarRepositoryInterface;
use App\Models\Calendar;
use App\Repositories\CalendarRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class CalendarController extends Controller
{
    protected CalendarRepositoryInterface $calendarRepository;

    public function __construct(CalendarRepository $calendarRepository)
    {
        $this->calendarRepository = $calendarRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        return new Response(new CalendarResourceCollection($this->calendarRepository->all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CalendarStoreRequest $request)
    {
        $response = [];
        $newItem = $this->calendarRepository->create($request->input());
        $response['item'] = new CalendarResource($newItem);
        $response['message'] = sprintf('Calendar %1$s successfully created', $newItem['title']);
        return new Response($response);
    }

    /**
     * Return a certain good
     */
    public function show(Calendar $calendar): Response
    {
        return new Response(new CalendarResource($calendar));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CalendarStoreRequest $request, Calendar $calendar)
    {
        $response = [];
        $newItem = $this->calendarRepository->update($request->input(), $calendar);
        $response['item'] = new CalendarResource($newItem);
        $response['message'] = sprintf('Calendar %1$s successfully updated', $newItem['title']);
        return new Response($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendar $calendar)
    {
        $calendar->delete();
        return new Response([
            'message' => sprintf('Calendar %1$s successfully deleted', $calendar['title'])
        ]);
    }
}
