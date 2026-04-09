<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('aduans', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->date('tanggal');
        $table->foreignId('kecamatan_id')->constrained()->cascadeOnDelete();
        $table->foreignId('desa_id')->constrained()->cascadeOnDelete();
        $table->text('alamat');
        $table->text('isi_aduan');
        $table->integer('titik');
        $table->enum('keterangan',['Dalam Proses','Sudah Tertangani']);
        $table->string('foto')->nullable();
        $table->timestamps();
    });
}
};
