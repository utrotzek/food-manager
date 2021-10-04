<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Calendar extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'color'];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function appointmentsForDay(Day $day): Collection
    {
        return $this->appointments()->where('day_id', $day->id)->get();
    }
}
