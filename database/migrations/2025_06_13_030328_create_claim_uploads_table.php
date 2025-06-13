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
        Schema::create('claim_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->integer('sheet_value')->nullable();
            $table->integer('recipe_value')->nullable();
            $table->integer('commercial_value')->nullable();
            $table->integer('tax_value')->nullable();
            $table->integer('total')->nullable();
            $table->foreignId('unitbisnis_code')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->string('period')->nullable();
            $table->boolean('is_valid')->default(false);
            $table->string('batch_id')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_uploads');
    }
};
