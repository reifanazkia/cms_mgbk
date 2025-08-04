<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_name',
        'email',
        'phone',
        'product_id',
        'price',
        'ongkir',
        'total',
        'payment_method',
        'reference',
        'payment_url',
        'status',
        'note',
        'province',    // tambahkan ini
        'city',        // tambahkan ini
        'address',     // tambahkan ini
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
