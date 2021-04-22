<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DayPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'  => $this->id,
            'recipe' => new RecipeLightResource($this->recipe),
            'meal' => new MealResource($this->meal),
            'day' => new DayResource($this->day),
            'description' => $this->description,
            'done' => $this->done
        ];
    }
}
