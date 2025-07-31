<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLogoNullableOnSosmedTable extends Migration
{
    public function up()
    {
        Schema::table('sosmed', function (Blueprint $table) {
            $table->string('logo')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('sosmed', function (Blueprint $table) {
            $table->string('logo')->nullable(false)->change();
        });
    }
}
