<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodGroup extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'sort'];
}
