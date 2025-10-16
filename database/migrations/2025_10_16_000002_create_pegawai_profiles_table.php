<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pegawai_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('unit_kerja', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pegawai_profiles');
    }
};
