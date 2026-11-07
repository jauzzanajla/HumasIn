<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opinis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul_opini');
            $table->string('penulis'); // Nama penulis / pejabat Eselon II
            $table->text('ringkasan');
            $table->string('file_naskah'); // File dokumen final opini (pdf/docx)
            $table->string('foto_penulis')->nullable();
            $table->string('status')->default('PENDING'); // PENDING, DISETUJUI, DITOLAK
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opinis');
    }
};