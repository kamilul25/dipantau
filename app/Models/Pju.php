<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pju extends Model
{
    protected $table = 'pju';

    protected $fillable = [
        'kecamatan',
        'desa',
        'rt',
        'rw',
        'pju',
        'pjuts',
        'tahun',
        'file_gpx'
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa');
    }
}
