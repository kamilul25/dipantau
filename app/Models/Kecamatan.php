<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
public function desas()
{
    return $this->hasMany(Desa::class);
}

public function perumahans()
{
    return $this->hasMany(Perumahan::class);
}

}
