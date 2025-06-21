<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
            $table->bigInteger('commercial_value')->nullable();
            $table->bigInteger('tax_value')->nullable();
            $table->bigInteger('total')->nullable();
            $table->integer('unitbisnis_code')->nullable();
            $table->foreign('unitbisnis_code')->references('unitbisnis_code')->on('branch_offices');
            $table->foreignId('user_id')->constrained();
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
