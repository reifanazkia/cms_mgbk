<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('customer_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->integer('price');
            $table->integer('ongkir')->default(0);
            $table->integer('total');
            $table->string('payment_method')->nullable();
            $table->string('reference')->nullable(); // dari Duitku
            $table->string('payment_url')->nullable(); // dari Duitku
            $table->string('status')->default('pending'); // pending, paid, expired
            $table->text('note')->nullable();
            $table->string('province'); // tambahkan kolom ini
            $table->string('city');     // tambahkan kolom ini
            $table->text('address');    // tambahkan kolom ini
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
