<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $fillable = [
        'job_type',
        'position_title',
        'ringkasan',
        'klasifikasi',
        'deskripsi',
        'pengalaman',
        'jam_kerja',
        'hari_kerja',
        'lokasi'
    ];

    protected $casts = [
        'klasifikasi' => 'array',
        'deskripsi' => 'array',
    ];
}
