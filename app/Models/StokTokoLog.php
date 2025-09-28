<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokTokoLog extends Model
{
    protected $fillable = ['product_id', 'stok_masuk', 'stok_keluar', 'keterangan', 'tanggal'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}