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
        Schema::table('tasks', function (Blueprint $table) {
            // statusカラムが存在しない場合のみ追加する
            if (!Schema::hasColumn('tasks', 'status')) {
                $table->string('status', 10)->default('pending'); // statusカラム追加
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // statusカラムが存在する場合のみ削除
            if (Schema::hasColumn('tasks', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};