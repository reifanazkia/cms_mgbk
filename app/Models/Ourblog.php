<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ourblog extends Model
{
    protected $table = 'ourblogs';

    protected $fillable = [
        'title',
        'description',
        'image',
        'pub_date',
        'category_id',
        'waktu_baca'

    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
