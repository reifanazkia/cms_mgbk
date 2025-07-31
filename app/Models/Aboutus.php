<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $table = 'aboutus'; // karena bukan plural default
    protected $fillable = ['sejarah', 'visi', 'misi'];

    public function photos()
    {
        return $this->hasMany(Aboutus_photo::class, 'aboutus_id');
    }
}
