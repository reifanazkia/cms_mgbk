<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TentangkamiCategory extends Model
{
    use HasFactory;

    protected $table = 'tentangkami_categories';

    protected $fillable = ['nama'];


    public function tentangkami()
    {
        return $this->hasMany(Tentangkami::class, 'category_tentangkami_id');
    }
}
