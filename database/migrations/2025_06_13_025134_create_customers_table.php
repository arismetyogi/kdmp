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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('segmen_standarisasi')->nullable();
            $table->string('area_code')->nullable();
            $table->string('area_code_description')->nullable();
            $table->string('customer_name')->nullable();
            $table->smallInteger('insurer_id')->nullable();
            $table->smallInteger('deleted_by')->nullable();
            $table->smallInteger('updated_by')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
