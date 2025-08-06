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
        Schema::create('bidang_terkait', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi');
            $table->timestamps();
        });
        Schema::create('bidang_terkait_klasifikasi', function (Blueprint $table) {
            $table->id();
            $table->integer('urutan')->nullable();
            $table->foreignIdFor(\App\Models\Klasifikasi::class)->constrained('klasifikasi')->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\BidangTerkait::class)->constrained('bidang_terkait')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidang_terkait_klasifikasi');
        Schema::dropIfExists('bidang_terkait');
    }
};
