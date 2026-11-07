<?php

use App\Models\DukunganKegiatan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengingat_kalenders', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 100);
            $table->date('tanggal');
            $table->time('jam')->nullable();
            $table->string('warna', 7)->default('#F59E0B');
            $table->string('keterangan', 255)->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Warna kalender untuk event (DukunganKegiatan). Null = pakai warna default.
        Schema::table((new DukunganKegiatan)->getTable(), function (Blueprint $table) {
            $table->string('warna_kalender', 7)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table((new DukunganKegiatan)->getTable(), function (Blueprint $table) {
            $table->dropColumn('warna_kalender');
        });

        Schema::dropIfExists('pengingat_kalenders');
    }
};