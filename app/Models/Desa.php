<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
public function kecamatan()
{
    return $this->belongsTo(Kecamatan::class);
}

public function perumahans()
{
    return $this->hasMany(Perumahan::class);
}

}
