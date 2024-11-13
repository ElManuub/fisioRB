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
        Schema::create('therapies', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->decimal('price');
            $table->decimal('discount_amount', 8, 2)->nullable(); 
            $table->date('discount_start')->nullable();  
            $table->date('discount_end')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('therapies');
    }
};
