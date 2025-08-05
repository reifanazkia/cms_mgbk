<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tentangkami extends Model
{
    protected $table = 'tentang_kami';
    protected $fillable = [
        'title',
        'category',
        'description',
        'image'

    ];
}
