<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Produk as ModelsProduk;
use App\Models\Kategori as ModelsKategori;

class Kategori extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $title = 'Kategori';
    public $nama_kategori;
    public $ngeditdata = false;
    public $kategori_id;
    public $kategori_selected_id = [];
    public $katakunci;
    public $sortColoumn = 'nama_kategori';
    public $sortDirection = 'asc';

    public function clear()
    {
        $this->nama_kategori = '';
        $this->ngeditdata = false;
        $this->kategori_id = '';
        $this->kategori_selected_id = [];
    }

    public function store()
    {
        $rules = [
            'nama_kategori' => 'required',
        ];
        $pesan = [
            'nama_kategori.required' => 'Nama wajib diisi',
        ];
        $validated = $this->validate($rules, $pesan);
        ModelsKategori::create($validated);
        session()->flash('message', 'Data berhasil ditambahkan');
        $this->clear();
    }

    public function edit($id)
    {
        $data = ModelsKategori::find($id);
        $this->nama_kategori = $data->nama_kategori;

        $this->ngeditdata = true;
        $this->kategori_id = $id;
    }

    public function update()
    {
        $rules = [
            'nama_kategori' => 'required',
        ];
        $pesan = [
            'nama_kategori.required' => 'Nama wajib diisi',
        ];
        $validated = $this->validate($rules, $pesan);
        $data = ModelsKategori::find($this->kategori_id);
        $data->update($validated);
        session()->flash('message', 'Data berhasil diedit');

        $this->clear();
    }

    public function delete()
    {
        if ($this->kategori_id != null) {
            $id = $this->kategori_id;
            ModelsKategori::find($id)->delete();
        }
        if (count($this->kategori_selected_id)) {
            for ($x = 0; $x < count($this->kategori_selected_id); $x++) {
                ModelsKategori::find($this->kategori_selected_id[$x])->delete();
            }
        }
        session()->flash('message', 'Data berhasil dihapus');
        $this->clear();
    }

    public function delete_confirmation($id)
    {
        if ($id != null) {
            $this->kategori_id = $id;
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
        $query = ModelsKategori::with('produks'); // Ini akan memuat semua produk yang terkait
        if ($this->katakunci != null) {
            $query->where('nama_kategori', 'like', '%' . $this->katakunci . '%');
            $data = $query->orderBy($this->sortColoumn, $this->sortDirection)->paginate(5);
        } else {
            $data = $query->orderBy($this->sortColoumn, $this->sortDirection)->paginate(5);
        }
        return view('livewire.kategori', ['dataKategori' => $data])->title('Kategori');
    }
}
