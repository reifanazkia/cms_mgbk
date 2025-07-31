<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hows extends Model
{
    protected $table = 'hows';

    protected $fillable = [
        'step_number',
        'title',
        'description',
        'image',
    ];
}
