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
        Schema::create('product_details', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->primary();
            $table->decimal('price', 8, 2); 
            $table->integer('stock'); 
            $table->integer('sold')->default(0); 
            $table->integer('discount')->default(0);
            $table->text('description')->nullable();
            $table->string('url')->default('https://example.com');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
