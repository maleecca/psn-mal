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
        Schema::create('klasifikasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignIdFor(\App\Models\Klasifikasi::class, 'parent_id')->nullable()->constrained('klasifikasi')->nullOnDelete();
            $table->timestamps();
        });
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->string('no_laporan')->unique();
            $table->string('bukti')->nullable();
            $table->text('label');
            $table->text('deskripsi');
            $table->foreignIdFor(\App\Models\Klasifikasi::class, 'klasifikasi_id')->constrained('klasifikasi')->cascadeOnDelete();
            $table->timestamps();
            $table->foreignIdFor(\App\Models\User::class)->nullable()->constrained('users')->cascadeOnDelete();
        });
        Schema::create('tindakan', function (Blueprint $table) {
            $table->id();
            $table->text('dokumen')->nullable();
            $table->text('dokumentasi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->foreignIdFor(\App\Models\User::class)->nullable()->constrained('users')->nullOnDelete();
            $table->foreignIdFor(\App\Models\Laporan::class, 'laporan_id')->constrained('laporan')->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('hukuman', function (Blueprint $table) {
            $table->id();
            $table->text('dokumen')->nullable();
            $table->foreignIdFor(\App\Models\Laporan::class, 'laporan_id')->constrained('laporan')->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class)->nullable()->constrained('users')->nullOnDelete();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
