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
        Schema::create('claim_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('upload_id');
            $table->foreign('upload_id')->references('id')->on('claim_uploads');
            $table->string('invoice_number')->nullable();
            $table->integer('invoice_value')->nullable();
            $table->string('delivery_date')->nullable();
            $table->string('upload_invoice_file')->nullable();
            $table->string('receipt_file')->nullable();
            $table->string('tax_invoice_file')->nullable();
            $table->string('invoice_date')->nullable();
            $table->string('po_customer_file')->nullable();
            $table->string('receipt_order_file')->nullable();
            $table->string('customer_tracking_number')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_details');
    }
};
