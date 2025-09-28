<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['shift_id', 'nomor_struk', 'tanggal_transaksi', 'total', 'uang_dibayar', 'kembalian'];

        protected $casts = [
        'tanggal_transaksi' => 'datetime', // ✅ biar otomatis jadi Carbon
    ];
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function packages()
    {
        return $this->hasMany(TransactionPackage::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}