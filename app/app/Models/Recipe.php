<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'rating', 'portion', 'comments', 'image'];
    protected $casts = [
        'rating' => 'float'
    ];

    public function setRatingAttribute($rating)
    {
        if ($rating) {
            $this->attributes['rating'] = str_replace(',', '.', $rating);
        } else {
            $this->attributes['rating'] = null;
        }
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function steps(): HasMany
    {
        return $this->hasMany(Step::class)->orderBy('sort');
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class);
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function ingredientCategories(): HasMany
    {
        return $this->hasMany(IngredientCategory::class);
    }
}
