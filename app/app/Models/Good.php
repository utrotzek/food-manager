<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Good extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'carbs', 'fat', 'protein', 'kcal', 'piece_in_gramm'];

    public function goodGroup(): BelongsTo
    {
        return $this->belongsTo(GoodGroup::class, 'good_group_id');
    }
}
