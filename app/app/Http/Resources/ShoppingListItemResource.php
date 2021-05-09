<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShoppingListItemResource extends JsonResource
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
            'unit' => new UnitResource($this->unit),
            'unitAmount' => $this->unit_amount,
            'good' => new GoodResource($this->good),
            'recipe_id' => $this->dayPlan->recipe->id,
            'shopping_list_id' => $this->shopping_list_id,
            'description' => $this->description
        ];
    }
}