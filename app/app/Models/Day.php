<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'done'];
    protected $dates = ['date'];

    public function __toString()
    {
        return $this->date->format('Y-m-d');
    }
}