<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokGudang extends Model
{
    protected $fillable = ['nama_barang', 'harga', 'stok_tersedia'];

    public function logs()
    {
        return $this->hasMany(StokGudangLog::class);
    }
}