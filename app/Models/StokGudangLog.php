<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokGudangLog extends Model
{
    protected $fillable = ['stok_gudang_id', 'stok_masuk', 'stok_keluar', 'keterangan', 'tanggal'];

    public function stokGudang()
    {
        return $this->belongsTo(StokGudang::class, 'stok_gudang_id');
    }
}