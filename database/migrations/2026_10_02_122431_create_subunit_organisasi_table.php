
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subunit_organisasi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('unit_id')
                ->constrained('unit')
                ->restrictOnDelete();

            $table->string('kode_subunit', 30)->unique();
            $table->string('nama_subunit');
            $table->enum('jenis', ['BAGIAN', 'SUBDIT', 'LAINNYA']);
            $table->boolean('aktif')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subunit_organisasi');
    }
};
