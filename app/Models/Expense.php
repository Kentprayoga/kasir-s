<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['shift_id', 'tanggal', 'keterangan', 'jumlah'];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}