<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'harga',
        'stok',
        'kategori_id',
        'supplier_id'
    ];

    // Tambahkan relasi ini
    public function kategoris()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
    public function suppliers()
    {
        return $this->belongsTo(supplier::class, 'supplier_id');
    }
}
