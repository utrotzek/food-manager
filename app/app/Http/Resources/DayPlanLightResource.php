<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DayPlanLightResource extends JsonResource
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
            'day' => new DayResource($this->day),
            'portion' => $this->portion,
        ];
    }
}
