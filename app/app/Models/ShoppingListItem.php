<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShoppingListItem extends Model
{
    use HasFactory;

    public $fillable = ['unit_amount', 'description'];

    public function unit(): belongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function shoppingList(): belongsTo
    {
        return $this->belongsTo(ShoppingList::class);
    }

    public function good(): belongsTo
    {
        return $this->belongsTo(Good::class);
    }

    public function dayPlan(): belongsTo
    {
        return $this->belongsTo(DayPlan::class);
    }

    public function __toString()
    {
        if ($this->description) {
            return (string)$this->description;
        } else {
            return $this->good->title;
        }
    }
}
