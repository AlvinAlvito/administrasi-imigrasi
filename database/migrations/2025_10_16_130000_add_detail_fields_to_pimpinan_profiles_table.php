<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pimpinan_profiles', function (Blueprint $table) {
            $table->enum('jenis_kelamin', ['L','P'])->nullable()->after('tanda_tangan');
            $table->string('eselon', 20)->nullable()->after('jenis_kelamin');
            $table->string('pangkat_gol', 50)->nullable()->after('eselon');
            $table->date('tmt')->nullable()->after('pangkat_gol');
            $table->string('pendidikan', 100)->nullable()->after('tmt');
            $table->string('diklat_teknis', 120)->nullable()->after('pendidikan');
        });
    }

    public function down(): void
    {
        Schema::table('pimpinan_profiles', function (Blueprint $table) {
            $table->dropColumn(['jenis_kelamin','eselon','pangkat_gol','tmt','pendidikan','diklat_teknis']);
        });
    }
};
