<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title',
        'image',
        'description',
        'price',
        'disusun',
        'jumlah_modul',
        'bahasa',
        'discount'
    ];

    protected $appends = ['final_price', 'formatted_price', 'formatted_final_price', 'name'];

    // Harga setelah diskon
    public function getFinalPriceAttribute()
    {
        if ($this->discount > 0) {
            return $this->price - ($this->price * $this->discount / 100);
        }
        return $this->price;
    }

    // Alias untuk name (karena template menggunakan name, tapi database menggunakan title)
    public function getNameAttribute()
    {
        return $this->title;
    }

    // Format harga asli
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // Format harga final
    public function getFormattedFinalPriceAttribute()
    {
        return 'Rp ' . number_format($this->final_price, 0, ',', '.');
    }

    // Cek apakah ada diskon
    public function getHasDiscountAttribute()
    {
        return $this->discount > 0;
    }

    // Hitung total diskon dalam rupiah
    public function getTotalDiscountAttribute()
    {
        if ($this->discount > 0) {
            return $this->price - $this->final_price;
        }
        return 0;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
