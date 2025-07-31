<?php

// app/Models/CategoryAnggota.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryAnggota extends Model
{
    use HasFactory;

    protected $table = 'anggota_categories';

    protected $fillable = ['name'];
}
