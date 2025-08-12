<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TentangKami extends Model
{
    use HasFactory;

    protected $table = 'tentang_kami';

    protected $fillable = [
        'display_on_home',
        'title',
        'category_tentangkami_id',
        'description',
        'image',
    ];

    protected $casts = [
        'display_on_home' => 'boolean',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    /**
     * Accessor: Ambil URL lengkap gambar.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset($this->image);
        }
        return null;
    }

    /**
     * Scope: Filter hanya data yang tampil di homepage.
     */
    public function scopeDisplayOnHome($query)
    {
        return $query->where('display_on_home', true);
    }

    /**
     * Relasi ke kategori tentang kami.
     * PERBAIKAN: Gunakan model TentangkamiCategory yang benar
     */
    public function category()
    {
        return $this->belongsTo(TentangkamiCategory::class, 'category_tentangkami_id');
    }
}
