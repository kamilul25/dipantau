<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perumahan extends Model
{
protected $fillable = [
    'nama_perumahan',
    'kecamatan_id',
    'desa_id',
    'alamat',
    'status',
    'jumlah_unit',
    'pengembang',
    'latitude',
    'longitude'
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
