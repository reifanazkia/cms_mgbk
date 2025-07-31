<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['title', 'image', 'description', 'price', 'discount'];

    // Harga setelah diskon
    public function getFinalPriceAttribute()
    {
        if ($this->discount > 0) {
            return $this->price - ($this->price * $this->discount / 100);
        }
        return $this->price;
    }
}
