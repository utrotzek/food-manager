<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Day extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'done'];
    protected $dates = ['date'];

    public function __toString()
    {
        return $this->date->format('Y-m-d');
    }

    public function dayPlans(): HasMany
    {
        return $this->hasMany(DayPlan::class);
    }

    public function getPendingDayPlansCount(): int
    {
        $pendingCount = 0;
        foreach ($this->dayPlans as $dayPlan) {
            if ($dayPlan->recipe && !$dayPlan->added_to_cart) {
                $pendingCount++;
            }
        }
        return $pendingCount;
    }
}
