<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dukungan_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // TU Eselon II yang mengajukan
            $table->unsignedBigInteger('admin_id')->nullable(); // PIC / Admin yang didelegasikan Super Admin

            // Informasi Event
            $table->string('nama_event');
            $table->date('tanggal_pelaksanaan');
            $table->string('hari');
            $table->string('bulan');
            $table->year('tahun');
            $table->time('jam_pelaksanaan');
            $table->string('lokasi');
            $table->string('pejabat_hadir')->nullable();
            $table->integer('jumlah_peserta')->nullable();

            // Support yang Dibutuhkan (Checkbox)
            $table->boolean('support_operator')->default(false);
            $table->boolean('support_desain_grafis')->default(false);
            $table->text('detail_desain_grafis')->nullable();
            $table->boolean('support_editing')->default(false);
            $table->text('detail_editing_platform')->nullable();
            $table->boolean('support_konten_web')->default(false);
            $table->boolean('support_merchandise')->default(false);
            $table->text('detail_merchandise')->nullable();
            $table->boolean('support_dokumentasi')->default(false);
            $table->boolean('support_tim_acara')->default(false);

            $table->string('status')->default('PENDING'); // PENDING, DIIDELEGASIKAN, DIPROSES, SELESAI, DITOLAK
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dukungan_kegiatans');
    }
};