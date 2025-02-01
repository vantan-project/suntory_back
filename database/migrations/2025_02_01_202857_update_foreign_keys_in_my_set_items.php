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
        Schema::table('my_set_items', function (Blueprint $table) {
            $table->foreign('drink_id')
                ->references('id')
                ->on('drinks')
                ->onDelete('cascade');
            
            $table->foreign('my_set_id')
                ->references('id')
                ->on('my_sets')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('my_set_items', function (Blueprint $table) {
            $table->dropForeign(['drink_id']);
            $table->dropForeign(['my_set_id']);
        });
    }
};
