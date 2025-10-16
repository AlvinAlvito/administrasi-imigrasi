<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('surat_perjalanan_dinas', function (Blueprint $table) {
            $table->id();
            // pegawai pengaju
            $table->foreignId('pegawai_id')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            // pimpinan penanggung jawab / approver
            $table->foreignId('pimpinan_id')
                  ->nullable()
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->string('no_surat', 80)->unique(); // contoh: WIM.2.IMI.IMI.6-UM.03.07-XXXX
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_berangkat');
            $table->date('tanggal_kembali');

            $table->string('tujuan', 255);
            $table->text('maksud_perjalanan')->nullable();
            $table->string('alat_transportasi', 100)->nullable();

            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('catatan_pimpinan')->nullable();

            $table->string('file_surat_pdf', 255)->nullable();

            $table->timestamps();

            // Index tambahan
            $table->index(['pegawai_id', 'status']);
            $table->index(['pimpinan_id', 'status']);
            $table->index('tanggal_pengajuan');
        });
    }

    public function down(): void {
        Schema::dropIfExists('surat_perjalanan_dinas');
    }
};
