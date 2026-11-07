<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama_kegiatan');
            $table->text('deskripsi');
            // Informasi 5W + 1H
            $table->text('what');
            $table->text('who');
            $table->date('when_tanggal');
            $table->string('where_lokasi');
            $table->text('why');
            $table->text('how');
            // File & Foto
            $table->string('foto_kegiatan')->nullable();
            $table->string('file_pendukung')->nullable();
            // Status: PENDING, DISETUJUI/DIPUBLLIKASIKAN, DITOLAK
            $table->string('status')->default('PENDING');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publikasis');
    }
};