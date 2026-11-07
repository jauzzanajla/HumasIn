<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
      
        Schema::table('notifikasis', function (Blueprint $table) {
            if (! Schema::hasColumn('notifikasis', 'url'))   $table->string('url')->nullable();
            if (! Schema::hasColumn('notifikasis', 'jenis')) $table->string('jenis', 30)->default('info');
        });
    }

    public function down(): void
    {
        Schema::table('notifikasis', function (Blueprint $table) {
            $table->dropColumn(['url', 'jenis']);
        });
    }
};