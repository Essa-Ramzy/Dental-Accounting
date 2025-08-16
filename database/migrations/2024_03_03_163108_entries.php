<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('teeth');
            $table->integer('amount');
            $table->decimal('unit_price', places: 2);
            $table->decimal('discount', places: 2);
            $table->decimal('price', places: 2);
            $table->decimal('cost', places: 2);
            $table->foreignId('customer_id')->constrained('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
