<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MealConfig extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function meals(): HasMany
    {
        return $this->hasMany(Meal::class);
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
