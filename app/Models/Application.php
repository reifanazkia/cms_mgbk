<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'career_id',
        'nama',
        'email',
        'no_telepon',
        'cover_letter',
        'file',
    ];

    /**
     * Relasi ke Career
     * Satu Application dimiliki oleh satu Career
     */
    public function career()
    {
        return $this->belongsTo(Career::class);
    }
}
