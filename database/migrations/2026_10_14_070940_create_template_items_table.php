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
      Schema::create('template_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained('template_categories')->onDelete('cascade');
    $table->string('judul');
    $table->string('file_path'); // Path file yang di-upload (.zip, .png, .pptx, dll)
    $table->string('tipe_file')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_items');
    }
};
