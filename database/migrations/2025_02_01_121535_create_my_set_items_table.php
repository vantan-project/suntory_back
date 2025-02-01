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
        Schema::create('my_set_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("my_set_id");
            $table->unsignedBigInteger("drink_id");
            $table->unsignedBigInteger("bottle_count");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_set_items');
    }
};
