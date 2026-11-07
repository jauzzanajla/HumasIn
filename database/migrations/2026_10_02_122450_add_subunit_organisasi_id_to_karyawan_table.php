
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->foreignId('subunit_organisasi_id')
                ->nullable()
                ->after('unit_id')
                ->constrained('subunit_organisasi')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subunit_organisasi_id');
        });
    }
};
