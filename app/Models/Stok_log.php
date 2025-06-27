<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stok_log extends Model
{
    use HasFactory;

    protected $fillable = [
        'produk_id',
        'jenis',
        'jumlah',
        'tanggal'
    ];

    // Tambahkan relasi ini
    public function produks()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
