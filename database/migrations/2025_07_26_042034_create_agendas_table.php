<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime')->nullable();
            $table->string('event_organizer')->nullable();
            $table->string('location');
            $table->string('register_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('type')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['Soldout', 'Open'])->default('Open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
