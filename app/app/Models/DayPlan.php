<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DayPlan extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'done', 'added_to_cart', 'portion'];

    protected $casts = [
        'added_to_cart' => 'boolean',
        'done' => 'boolean',
    ];

    public function meal(): belongsTo
    {
        return $this->belongsTo(Meal::class);
    }

    public function recipe(): belongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function day(): belongsTo
    {
        return $this->belongsTo(Day::class);
    }

    public function __toString()
    {
        if ($this->recipe) {
            return "plan ". $this->recipe->title;
        } elseif ($this->description) {
            return "plan ". $this->description;
        }
        return "";
    }
}
