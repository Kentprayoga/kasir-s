<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = ['tanggal', 'jam_mulai', 'jam_selesai', 'modal_awal', 'modal_akhir', 'total_penjualan', 'user_id'];

        public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeAktif($query)
{
    return $query->whereNull('jam_selesai');
}

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function getTotalPenjualanAttribute()
    {
        return $this->transactions()->sum('total');
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}