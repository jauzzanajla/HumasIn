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
       Schema::create('dokumentasi_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained('dokumentasi_categories')->onDelete('cascade');
    $table->string('judul');
    $table->text('link_url'); // Link Google Drive
    $table->text('keterangan')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_items');
    }
};
