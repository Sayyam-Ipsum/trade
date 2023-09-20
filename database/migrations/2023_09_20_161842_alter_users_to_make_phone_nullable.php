<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('users', 'phone_number')) {
            Schema::table("users", function (Blueprint $table) {
                DB::statement('alter table users modify phone_number varchar(255) null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_phone_nullable', function (Blueprint $table) {
            //
        });
    }
};
