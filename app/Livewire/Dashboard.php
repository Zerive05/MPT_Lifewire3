<?php

namespace App\Livewire;

use App\Models\Kategori as ModelsKategori;
use App\Models\supplier as ModelsSupplier;
use App\Models\Produk as ModelsProduk;
use App\Models\Penjualan as ModelsPenjualan;
use App\Models\Stok_log as ModelsStoklog;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $produk = ModelsProduk::count();
        $kategori = ModelsKategori::count();
        $supplier = ModelsSupplier::count();
        $penjualan = ModelsPenjualan::count();
        $stoklog = ModelsStoklog::count();
        return view('livewire.dashboard', [
            'produk' => $produk,
            'kategori' => $kategori,
            'supplier' => $supplier,
            'penjualan' => $penjualan,
            'stoklog' => $stoklog
        ])
            ->title('Dashboard');
    }
}
