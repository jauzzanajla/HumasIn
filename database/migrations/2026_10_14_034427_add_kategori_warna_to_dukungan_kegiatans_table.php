<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dukungan_kegiatans', function (Blueprint $table) {
            $table->string('kategori_warna')->default('hijau')->after('status'); // hijau, oranye, biru, coklat
        });
    }

    public function down(): void
    {
        Schema::table('dukungan_kegiatans', function (Blueprint $table) {
            $table->dropColumn('kategori_warna');
        });
    }
};