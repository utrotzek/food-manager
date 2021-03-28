<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = ['unit_amount'];
    protected $casts = [
        'unit_amount' => 'float'
    ];

    public function setUnitAmountAttribute($amount)
    {
        $this->attributes['unit_amount'] = str_replace(',', '.', $amount);
    }

    public function good(): BelongsTo
    {
        return $this->belongsTo(Good::class, 'good_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(IngredientCategory::class);
    }
}
