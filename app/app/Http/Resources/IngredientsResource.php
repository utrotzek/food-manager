<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IngredientsResource extends JsonResource
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
            'id' => $this->id,
            'category' => $this->category->id ?? null,
            'unit_amount' => $this->unit_amount,
            'unit' => new UnitResource($this->unit),
            'good' => new GoodResource($this->good),
        ];
    }
}
