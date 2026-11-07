<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ruangan', function (Blueprint $table) {
            $table->id();

            $table->string('nama_ruangan');

            $table->string('lokasi')->nullable();

            $table->unsignedInteger('kapasitas')->default(0);

            $table->unsignedInteger('kuota')->default(0);

            $table->unsignedInteger('durasi_maksimal')->default(1);

            $table->enum('satuan_durasi', [
                'MENIT',
                'JAM',
                'HARI',
            ])->default('JAM');

            $table->enum('status', [
                'TERSEDIA',
                'TIDAK_TERSEDIA',
            ])->default('TERSEDIA');

            $table->text('deskripsi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruangan');
    }
};