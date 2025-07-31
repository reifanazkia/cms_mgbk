<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';
    protected $fillable = [
        'category_anggota_id',
        'name',
        'title',
        'email',
        'phone_number',
        'facebook_id',
        'instagram_id',
        'tiktok_id',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(CategoryAnggota::class, 'category_anggota_id');
    }
}
