<?php

namespace App\Http\Controllers;

use App\Http\Requests\RangeRequest;
use App\Http\Resources\DayResource;
use App\Models\Day;
use App\Repositories\DayRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DayController extends Controller
{
    protected DayRepository $dayRepository;

    public function __construct(DayRepository $dayRepository)
    {
        $this->dayRepository = $dayRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new Response(
            DayResource::collection($this->dayRepository->all())
        );
    }

    public function range(RangeRequest $request): Response
    {
        $from = new Carbon($request->input('from'));
        $to = new Carbon($request->input('to'));

        return new Response(
            DayResource::collection(
                $this->dayRepository->findByRange($from, $to)
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
        $newItem = $this->dayRepository->create($request->input());
        return new Response([
            'message' => sprintf('Day %1$s successfully created', $newItem['date']->format('Y-m-d')),
            'item' => new DayResource($newItem)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Day $day)
    {
        return new Response(
            new DayResource($day)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Day $day): Response
    {
        $updatedItem = $this->dayRepository->update($request->input(), $day);
        return new Response([
            'message' => sprintf('Day %1$s has successfully be updated', $day['date']->format('Y-m-d')),
            'item' => new DayResource($updatedItem)
        ]);
    }

    public function shoppingDay(Request $request, Day $day): Response
    {
        $isShoppingDay = $request->input('isShoppingDay');
        $updatedItem = $this->dayRepository->update(['shopping_day' => $isShoppingDay], $day);

        if ($isShoppingDay) {
            $message = 'Day %1$s has successfully marked as shopping day';
        } else {
            $message = 'Day %1$s has successfully unmarked as shopping day';
        }

        return new Response([
            'message' => sprintf($message, $day['date']->format('Y-m-d')),
            'item' => new DayResource($updatedItem)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Day $day)
    {
        $day->delete();
        return new Response(
            sprintf('Day %1$s sucessfully deleted', $day['date']->format('Y-m-d'))
        );
    }
}
