<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidersTable extends Migration
{
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->boolean('display_on_home')->default(false);
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->string('image')->nullable();
            $table->string('youtube_id')->nullable();
            $table->string('button_text')->nullable();
            $table->string('url_link')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sliders');
    }
}
