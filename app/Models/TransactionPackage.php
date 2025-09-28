<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionPackage extends Model
{
    protected $fillable = ['transaction_id', 'package_id', 'qty', 'subtotal'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}