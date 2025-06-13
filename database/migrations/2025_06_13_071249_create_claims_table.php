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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->foreignId('unitbisnis_code');
            $table->bigInteger('value')->nullable();
            $table->string('period')->nullable();
            $table->bigInteger('invoice_value')->nullable();
            $table->boolean('is_editable')->default(false);
            $table->foreignId('user_id');
            $table->foreignId('upload_id');
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
