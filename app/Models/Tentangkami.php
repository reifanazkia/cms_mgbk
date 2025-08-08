<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TentangKami extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'tentang_kami';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'display_on_home',
        'title',
        'category',
        'description',
        'image',
    ];

    // Casting tipe data
    protected $casts = [
        'display_on_home' => 'boolean',
    ];
}
