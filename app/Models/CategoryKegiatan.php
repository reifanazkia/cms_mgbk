<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryKegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_categories';

    protected $fillable = ['name'];
}
