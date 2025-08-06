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
        Schema::create('bidang_terkait_petugas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\BidangTerkait::class, 'bidang_terkait_id')->constrained('bidang_terkait')->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class, 'petugas_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
