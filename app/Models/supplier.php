<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_supplier',
        'alamat',
        'telepon'
    ];

    // Tambahkan relasi ini
    public function produks()
    {
        return $this->hasMany(Produk::class, 'supplier_id', 'id');
    }
}
