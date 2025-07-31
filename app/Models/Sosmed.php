<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sosmed extends Model
{
    protected $table = 'sosmed';

    protected $fillable = [
        'logo',
        'nama',
        'link',
    ];


}
