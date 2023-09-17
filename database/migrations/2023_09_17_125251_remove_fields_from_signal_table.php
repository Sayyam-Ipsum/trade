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
        Schema::table('signals', function (Blueprint $table) {
            $table->dropColumn(['type', 'amount']);
            $table->enum('result',['profit', 'loss', 'none'])->nullable()->change();
            $table->enum('status',['in-progress', 'completed'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('signals', function (Blueprint $table) {
            //
        });
    }
};
