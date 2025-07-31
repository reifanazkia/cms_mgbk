<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->enum('job_type', ['Full Time', 'Part Time', 'Contract']);
            $table->string('position_title');
            $table->text('ringkasan')->nullable();
            $table->json('klasifikasi')->nullable(); // array
            $table->json('deskripsi')->nullable();   // array
            $table->string('pengalaman')->nullable();
            $table->string('jam_kerja')->nullable();
            $table->string('hari_kerja')->nullable();
            $table->string('lokasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
