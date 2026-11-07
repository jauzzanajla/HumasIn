<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dokumentasi_categories', function (Blueprint $table) {
    $table->id();
    $table->string('nama_kategori'); // Contoh: Rapat Koordinasi, Kunjungan Kerja
    $table->string('slug')->unique();
    $table->text('deskripsi')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_categories');
    }
};
