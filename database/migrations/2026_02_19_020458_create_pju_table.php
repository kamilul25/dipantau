<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pju', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan')->constrained('kecamatans')->onDelete('cascade');
            $table->foreignId('desa')->constrained('desas')->onDelete('cascade');
            $table->integer('rt');
            $table->integer('rw');
            $table->integer('pju');
            $table->integer('pjuts');
            $table->integer('tahun');
            $table->string('file_gpx')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pju');
    }
};
