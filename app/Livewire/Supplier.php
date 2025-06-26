<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\supplier as ModelsSupplier;

class Supplier extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $title = 'Supplier';
    public $nama_supplier;
    public $alamat;
    public $telepon;
    public $ngeditdata = false;
    public $supplier_id;
    public $supplier_selected_id = [];
    public $katakunci;
    public $sortColoumn = 'nama_supplier';
    public $sortDirection = 'asc';

    public function clear()
    {
        $this->nama_supplier = '';
        $this->alamat = '';
        $this->telepon = '';

        $this->ngeditdata = false;
        $this->supplier_id = '';
        $this->supplier_selected_id = [];
    }

    public function store()
    {
        $rules = [
            'nama_supplier' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
        ];
        $pesan = [
            'nama_supplier.required' => 'Nama wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'telepon.required' => 'Telepon wajib diisi',
        ];
        $validated = $this->validate($rules, $pesan);
        ModelsSupplier::create($validated);
        session()->flash('message', 'Data berhasil ditambahkan');
        $this->clear();
    }

    public function edit($id)
    {
        $data = ModelsSupplier::find($id);
        $this->nama_supplier = $data->nama_supplier;
        $this->alamat = $data->alamat;
        $this->telepon = $data->telepon;

        $this->ngeditdata = true;
        $this->supplier_id = $id;
    }

    public function update()
    {
        $rules = [
            'nama_supplier' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
        ];
        $pesan = [
            'nama_supplier.required' => 'Nama wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'telepon.required' => 'Telepon wajib diisi',
        ];
        $validated = $this->validate($rules, $pesan);
        $data = ModelsSupplier::find($this->supplier_id);
        $data->update($validated);
        session()->flash('message', 'Data berhasil diedit');

        $this->clear();
    }

    public function delete()
    {
        if ($this->supplier_id != null) {
            $id = $this->supplier_id;
            ModelsSupplier::find($id)->delete();
        }
        if (count($this->supplier_selected_id)) {
            for ($x = 0; $x < count($this->supplier_selected_id); $x++) {
                ModelsSupplier::find($this->supplier_selected_id[$x])->delete();
            }
        }
        session()->flash('message', 'Data berhasil dihapus');
        $this->clear();
    }

    public function delete_confirmation($id)
    {
        if ($id != null) {
            $this->supplier_id = $id;
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
        $query = ModelsSupplier::with('produks'); // Ini akan memuat semua produk yang terkait
        if ($this->katakunci != null) {
            $query->where('nama_supplier', 'like', '%' . $this->katakunci . '%')
                ->orWhere('alamat', 'like', '%' . $this->katakunci . '%')
                ->orWhere('telepon', 'like', '%' . $this->katakunci . '%');
            $data = $query->orderBy($this->sortColoumn, $this->sortDirection)->paginate(5);
        } else {
            $data = $query->orderBy($this->sortColoumn, $this->sortDirection)->paginate(5);
        }
        return view('livewire.supplier', ['datasupplier' => $data])->title('Supplier');
    }
}
