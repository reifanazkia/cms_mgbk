<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('email');
            $table->string('phone');
            $table->string('province');
            $table->string('city');
            $table->text('address');
            $table->text('note')->nullable();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('price'); // harga yang dibayar (setelah diskon)
            $table->string('payment_method')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
