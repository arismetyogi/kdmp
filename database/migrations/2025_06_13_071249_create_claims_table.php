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
            $table->foreignId('customer_id')->constrained();
            $table->integer('unitbisnis_code')->nullable();
            $table->foreign('unitbisnis_code')->references('unitbisnis_code')->on('branch_offices');
            $table->bigInteger('value')->nullable();
            $table->string('period')->nullable();
            $table->bigInteger('invoice_value')->nullable();
            $table->boolean('is_editable')->default(false);
            $table->foreignId('user_id')->constrained();
            $table->unsignedBigInteger('upload_id')->nullable();
            $table->foreign('upload_id')->references('id')->on('claim_uploads');
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
