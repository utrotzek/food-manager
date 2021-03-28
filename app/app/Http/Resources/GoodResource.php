<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoodResource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

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
            'title' => $this->title,
            'carbs' => $this->carbs,
            'fat' => $this->fat,
            'protein' => $this->protein,
            'kcal' => $this->kcal,
            'piece_in_gramm' => $this->piece_in_gramm,
            'group' => new GoodGroupResource($this->goodGroup()->first())
        ];
    }
}
