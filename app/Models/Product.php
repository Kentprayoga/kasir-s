<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['nama_barang', 'harga', 'stok'];

    public function stokTokoLogs()
    {
        return $this->hasMany(StokTokoLog::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function packageItems()
    {
        return $this->hasMany(PackageItem::class);
    }

}