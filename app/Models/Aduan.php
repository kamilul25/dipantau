<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aduan extends Model
{
protected $fillable = [
    'nama',
    'tanggal',
    'kecamatan_id',
    'desa_id',
    'alamat',
    'isi_aduan',
    'titik',
    'keterangan',
    'foto'
];

protected $casts = [
    'tanggal' => 'date',  // atau 'datetime' jika kolom datetime
];

public function kecamatan()
{
    return $this->belongsTo(Kecamatan::class);
}

public function desa()
{
    return $this->belongsTo(Desa::class);
}
}
