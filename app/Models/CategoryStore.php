<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryStore extends Model
{
    use HasFactory;

    protected $table = 'store_categories';

    protected $fillable = ['name'];

}
