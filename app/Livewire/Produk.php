<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use App\Models\Produk as ModelsProduk;
use App\Models\Kategori as ModelsKategori;
use App\Models\supplier as ModelsSupplier;

class Produk extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $title = 'Produk';
    public $nama_produk;
    public $harga;
    public $stok;
    public $kategori_id;
    public $supplier_id;
    public $nama_kategori;
    public $nama_supplier;
    public $ngeditdata = false;
    public $produk_id;
    public $produk_selected_id = [];
    public $katakunci;
    public $sortColoumn = 'nama_produk';
    public $sortDirection = 'asc';

    public function clear()
    {
        $this->nama_produk = '';
        $this->harga = '';
        $this->stok = '';
        $this->kategori_id = '';
        $this->supplier_id = '';

        $this->ngeditdata = false;
        $this->produk_id = '';
        $this->produk_selected_id = [];
    }

    public function store()
    {
        $rules = [
            'nama_produk' => 'required',
            'harga' => 'required',
            'stok' => 'required',
            'kategori_id' => 'required',
            'supplier_id' => 'required',
        ];
        $pesan = [
            'nama_produk.required' => 'Nama wajib diisi',
            'harga.required' => 'Alamat wajib diisi',
            'stok.required' => 'Telepon wajib diisi',
            'kategori_id.required' => 'Kategori wajib diisi',
            'supplier_id.required' => 'Supplier wajib diisi',
        ];
        $validated = $this->validate($rules, $pesan);
        ModelsProduk::create($validated);
        session()->flash('message', 'Data berhasil ditambahkan');
        $this->clear();
    }

    public function edit($id)
    {
        $data = ModelsProduk::find($id);
        $this->nama_produk = $data->nama_produk;
        $this->harga = $data->harga;
        $this->stok = $data->stok;
        $this->kategori_id = $data->kategori_id;
        $this->supplier_id = $data->supplier_id;

        $this->ngeditdata = true;
        $this->produk_id = $id;
    }

    public function update()
    {
        $rules = [
            'nama_produk' => 'required',
            'harga' => 'required',
            'stok' => 'required',
            'kategori_id' => 'required',
            'supplier_id' => 'required',
        ];
        $pesan = [
            'nama_produk.required' => 'Nama wajib diisi',
            'harga.required' => 'Alamat wajib diisi',
            'stok.required' => 'Telepon wajib diisi',
            'kategori_id.required' => 'Kategori wajib diisi',
            'supplier_id.required' => 'Supplier wajib diisi',
        ];
        $validated = $this->validate($rules, $pesan);
        $data = ModelsProduk::find($this->produk_id);
        $data->update($validated);
        session()->flash('message', 'Data berhasil diedit');

        $this->clear();
    }

    public function delete()
    {
        if ($this->produk_id != null) {
            $id = $this->produk_id;
            ModelsProduk::find($id)->delete();
        }
        if (count($this->produk_selected_id)) {
            for ($x = 0; $x < count($this->produk_selected_id); $x++) {
                ModelsProduk::find($this->produk_selected_id[$x])->delete();
            }
        }
        session()->flash('message', 'Data berhasil dihapus');
        $this->clear();
    }

    public function delete_confirmation($id)
    {
        if ($id != null) {
            $this->produk_id = $id;
        } else {
        }
    }

    public function sort($colomnName)
    {
        $this->sortColoumn = $colomnName;
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function render()
    {
        $query = ModelsProduk::with('kategoris')->with('suppliers'); // Ini akan memuat semua produk yang terkait
        if ($this->katakunci != null) {
            $query->where('nama_produk', 'like', '%' . $this->katakunci . '%')
                ->orWhere('harga', 'like', '%' . $this->katakunci . '%')
                ->orWhere('stok', 'like', '%' . $this->katakunci . '%')
                ->orWhereHas('kategoris', function ($queryKategori) {
                    $queryKategori->where('nama_kategori', 'like', '%' . $this->katakunci . '%');
                })
                ->orWhereHas('suppliers', function ($querySupplier) {
                    $querySupplier->where('nama_supplier', 'like', '%' . $this->katakunci . '%');
                });
            $data = $query->orderBy($this->sortColoumn, $this->sortDirection)->paginate(5);
        } else {
            $data = $query->orderBy($this->sortColoumn, $this->sortDirection)->paginate(5);
        }
        $kategori = ModelsKategori::all();
        $supplier = ModelsSupplier::all();
        return view('livewire.produk', ['dataproduk' => $data, 'kategori' => $kategori, 'supplier' => $supplier])->title('Produk');
    }
}
