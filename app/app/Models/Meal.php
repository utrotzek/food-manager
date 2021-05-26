<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title', 'sort'];

    public function mealConfig(): belongsTo
    {
        return $this->belongsTo(MealConfig::class);
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
