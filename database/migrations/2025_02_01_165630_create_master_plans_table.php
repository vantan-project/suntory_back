<?php

use App\Models\MasterPlan;
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
        Schema::create('master_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("quantity");
            $table->unsignedInteger("price");
            $table->timestamps();
        });

        MasterPlan::insert([
            ['quantity' => 12, 'price' => 1380],
            ['quantity' => 24, 'price' => 2640],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_plans');
    }
};
