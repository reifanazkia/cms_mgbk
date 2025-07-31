<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';
    protected $fillable = [
        'category_kegiatan_id',
        'title',
        'description',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(CategoryKegiatan::class, 'category_kegiatan_id');
    }
}
