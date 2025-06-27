<?php

use App\Livewire\Dashboard;
use App\Livewire\Kategori;
use App\Livewire\Penjualan;
use App\Livewire\Produk;
use App\Livewire\Stoklog;
use App\Livewire\Supplier;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class);
Route::get('/produk', Produk::class);
Route::get('/kategori', Kategori::class);
Route::get('/supplier', Supplier::class);
Route::get('/penjualan', Penjualan::class);
Route::get('/stoklog', Stoklog::class);
