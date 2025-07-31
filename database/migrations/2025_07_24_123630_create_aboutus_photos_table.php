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
        Schema::create('aboutus_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aboutus_id');
            $table->string('photo_path');
            $table->timestamps();
            $table->foreign('aboutus_id')->references('id')->on('aboutus')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aboutus_photos');
    }
};
