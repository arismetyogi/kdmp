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
        Schema::create('branch_offices', function (Blueprint $table) {
            $table->id();
            $table->integer('unitbisnis_code')->index();
            $table->string('name')->nullable();
            $table->string('alamat_bm')->nullable();
            $table->string('flag')->nullable();
            $table->string('nama_email')->nullable();
            $table->string('flag_bm')->nullable();
            $table->string('kode_entitas')->nullable();
            $table->string('entitas')->nullable();
            $table->string('status_cetak_sk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_offices');
    }
};
