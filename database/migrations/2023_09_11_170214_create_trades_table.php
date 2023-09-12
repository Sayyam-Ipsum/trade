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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable(false);
            $table->unsignedBigInteger("signal_id")->nullable();
            $table->enum("type", ["buy", "sell"])->nullable(false);
            $table->double("amount", 8, 2)->nullable(false);
            $table->double("profitable_amount", 8, 2)->nullable(false);
            $table->enum("result", ["profit", "loss", "pending"])->default("pending");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
