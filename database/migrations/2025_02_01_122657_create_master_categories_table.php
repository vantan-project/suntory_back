<?php

use App\Models\MasterCategory;
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
        Schema::create('master_categories', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->timestamps();
        });

        MasterCategory::insert([
            ['name' => 'コーヒー飲料'],
            ['name' => 'お茶飲料'],
            ['name' => '特定保健用食品'],
            ['name' => 'ミネラルウォーター'],
            ['name' => '炭酸飲料'],
            ['name' => '果汁入り飲料'],
            ['name' => '紅茶飲料'],
            ['name' => 'スポーツ飲料他'],
            ['name' => '乳性飲料'],
            ['name' => '野菜系飲料'],
            ['name' => 'その他'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_categories');
    }
};
