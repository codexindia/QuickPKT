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
        Schema::create('operator_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_code');
            $table->enum('type', ['mobile_recharge', 'electricity']);
            $table->enum('commission_type', ['fixed_amount', 'percentage']);
            $table->string('commission_value');
            $table->string('status')->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operator_lists');
    }
};
