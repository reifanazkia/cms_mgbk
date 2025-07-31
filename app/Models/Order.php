<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_name', 'email', 'phone', 'province', 'city',
        'address', 'note', 'product_id', 'price', 'payment_method', 'ongkir'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}


