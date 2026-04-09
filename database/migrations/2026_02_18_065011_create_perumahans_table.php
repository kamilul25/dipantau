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
    Schema::create('perumahans', function (Blueprint $table) {
        $table->id();
        $table->string('nama_perumahan');
        $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
        $table->foreignId('desa_id')->constrained()->onDelete('cascade');
        $table->text('alamat');
        $table->string('status');
        $table->integer('jumlah_unit');
        $table->string('pengembang');
        $table->string('latitude');
        $table->string('longitude');
        $table->timestamps();
    });
}

};
