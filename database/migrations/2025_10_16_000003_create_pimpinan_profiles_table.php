<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pimpinan_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->string('nip', 30)->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->string('tanda_tangan', 255)->nullable(); // path file tt
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pimpinan_profiles');
    }
};
