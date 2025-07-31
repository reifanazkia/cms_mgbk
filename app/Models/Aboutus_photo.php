<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aboutus_photo extends Model
{
    protected $table = 'aboutus_photos';
    protected $fillable = ['aboutus_id', 'photo_path'];

    public function aboutus()
    {
        return $this->belongsTo(AboutUs::class, 'aboutus_id');
    }
}
