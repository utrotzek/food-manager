<?php

namespace App\Http\Resources;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @see Recipe
 * @method HasMany steps()
 * @method BelongsToMany tags()
 * @method BelongsToMany ingredients()
 */
class RecipeLightResource extends JsonResource
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
            'title' => $this->title,
            'image' => $this->image,
            'rating' => $this->rating,
            'portion' => $this->portion,
            'comments' => $this->comments,
            'favorite' => $this->favorite,
            'remember' => $this->remember,
            'tags' => new TagResourceCollection($this->tags()->get()),
        ];
    }
}
