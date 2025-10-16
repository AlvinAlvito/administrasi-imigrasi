<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rincian_biaya', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')
                  ->constrained('surat_perjalanan_dinas')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            // kamu bisa pecah per item juga, tapi sesuai rancangan ringkas:
            $table->decimal('uang_harian', 15, 2)->default(0);
            $table->decimal('transportasi', 15, 2)->default(0);
            $table->decimal('jumlah_total', 15, 2)->default(0);
            $table->string('terbilang', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('rincian_biaya');
    }
};
