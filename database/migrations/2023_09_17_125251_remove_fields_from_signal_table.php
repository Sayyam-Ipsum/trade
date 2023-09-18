<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->enum('result',['profit', 'loss', 'none'])->change();
            $table->enum('status',['in-progress', 'completed'])->change();
            $table->timestamp('created_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'))->change();
            $table->timestamp('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->change();
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
