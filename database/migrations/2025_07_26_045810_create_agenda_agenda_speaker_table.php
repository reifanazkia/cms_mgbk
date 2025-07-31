<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda_agenda_speaker', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agenda_id')->constrained()->onDelete('cascade');
            $table->foreignId('agenda_speaker_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_agenda_speaker');
    }
};
