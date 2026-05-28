<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('Invoice');
            $table->string('StockCode');
            $table->text('Description');
            $table->integer('Quantity');
            $table->string('InvoiceDate'); // sementara string dulu
            $table->decimal('Price', 10, 2);
            $table->string('CustomerID')->nullable();
            $table->string('Country');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};