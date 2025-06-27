<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'produk_id',
        'jumlah',
        'tanggal'
    ];

    // Tambahkan relasi ini
    public function produks()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
