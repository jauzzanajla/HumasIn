<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aset', function (Blueprint $table) {
            $table->id();

            $table->string('nama_aset');

            $table->string('foto')->nullable();

            $table->text('deskripsi')->nullable();

            $table->unsignedInteger('jumlah')->default(0);

            $table->string('status')->default('TERSEDIA');

            $table->string('kondisi')->default('BAIK');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aset');
    }
};