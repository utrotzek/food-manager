<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppState extends Model
{
    use HasFactory;

    protected $fillable = ['state_value'];

    public function __toString()
    {
        return $this->state_name;
    }
}
