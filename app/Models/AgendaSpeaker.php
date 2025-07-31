<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaSpeaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'agenda_id',
        'name',
        'title',
        'photo',
    ];

    public function agendas()
    {
        return $this->belongsToMany(Agenda::class, 'agenda_agenda_speaker', 'agenda_speaker_id', 'agenda_id');
    }
}
