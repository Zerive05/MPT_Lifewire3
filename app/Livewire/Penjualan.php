<?php

namespace App\Livewire;

use App\Models\Penjualan as ModelsPenjualan;
use App\Models\Produk as ModelsProduk;
use Livewire\Component;
use Livewire\WithPagination;

class Penjualan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $title = 'Penjualan';
    public $produk_id;
    public $jumlah;
    public $tanggal;
    public $ngeditdata = false;
    public $penjualan_id;
    public $penjualan_selected_id = [];
    public $katakunci;
    public $sortColoumn = 'nama_produk';
    public $sortDirection = 'asc';

    public function clear()
    {
        $this->produk_id = '';
        $this->jumlah = '';
        $this->tanggal = '';

        $this->ngeditdata = false;
        $this->penjualan_id = '';
        $this->penjualan_selected_id = [];
    }

    public function store()
    {
        $rules = [
            'produk_id' => 'required',
            'jumlah' => 'required',
            'tanggal' => 'required',
        ];
        $pesan = [
            'produk_id.required' => 'Nama wajib diisi',
            'jumlah.required' => 'Jumlah wajib diisi',
            'tanggal.required' => 'Tanggal wajib diisi',
        ];
        $validated = $this->validate($rules, $pesan);
        // Ambil produk berdasarkan produk_id
        $produk = ModelsProduk::find($validated['produk_id']);

        // Pastikan produk ditemukan dan stok mencukupi
        if ($produk && $produk->stok >= $validated['jumlah']) {
            // Kurangi stok produk
            $produk->stok -= $validated['jumlah'];
            $produk->save(); // Simpan perubahan stok

            // Buat entri penjualan
            ModelsPenjualan::create($validated);

            session()->flash('message', 'Data penjualan berhasil ditambahkan dan stok produk diperbarui!');
            $this->clear(); // Pastikan metode clear() Anda ada dan berfungsi
        } else {
            // Jika produk tidak ditemukan atau stok tidak mencukupi
            session()->flash('error', 'Stok produk tidak mencukupi atau produk tidak ditemukan.');
            $this->clear(); // Pastikan metode clear() Anda ada dan berfungsi
            // Anda bisa tambahkan validasi errors() jika ingin menampilkan pesan error spesifik di form
            $this->addError('jumlah', 'Stok tidak mencukupi.');
        }
    }

    public function edit($id)
    {
        $data = ModelsPenjualan::find($id);
        $this->produk_id = $data->produk_id;
        $this->jumlah = $data->jumlah;
        $this->tanggal = $data->tanggal;

        $this->ngeditdata = true;
        $this->penjualan_id = $id;
    }

    public function update()
    {
        $rules = [
            'produk_id' => 'required',
            'jumlah' => 'required',
            'tanggal' => 'required',
        ];
        $pesan = [
            'produk_id.required' => 'Nama wajib diisi',
            'jumlah.required' => 'Jumlah wajib diisi',
            'tanggal.required' => 'Tanggal wajib diisi',
        ];
        $validated = $this->validate($rules, $pesan);
        $data = ModelsPenjualan::find($this->penjualan_id);
        $data->update($validated);
        session()->flash('message', 'Data berhasil diedit');

        $this->clear();
    }

    public function delete()
    {
        if ($this->penjualan_id != null) {
            $id = $this->penjualan_id;
            ModelsPenjualan::find($id)->delete();
        }
        if (count($this->penjualan_selected_id)) {
            for ($x = 0; $x < count($this->penjualan_selected_id); $x++) {
                ModelsPenjualan::find($this->penjualan_selected_id[$x])->delete();
            }
        }
        session()->flash('message', 'Data berhasil dihapus');
        $this->clear();
    }

    public function delete_confirmation($id)
    {
        if ($id != null) {
            $this->penjualan_id = $id;
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
        $query = ModelsPenjualan::with('produks'); // Ini akan memuat semua produk yang terkait

        // Jika kolom pengurutan adalah 'nama_produk', lakukan JOIN dan select kolom
        if ($this->sortColoumn == 'nama_produk') {
            $query->join('produks', 'penjualans.produk_id', '=', 'produks.id')
                ->select('penjualans.*', 'produks.nama_produk as product_name_for_sort'); // Alias untuk nama_produk
            // Mengubah kolom pengurutan menjadi alias yang baru dibuat
        }

        // Filter berdasarkan kata kunci
        if ($this->katakunci != null) {
            $query->where(function ($q) {
                // Cari di nama_produk melalui relasi
                $q->whereHas('produks', function ($queryProduk) {
                    $queryProduk->where('nama_produk', 'like', '%' . $this->katakunci . '%');
                })
                    // Cari di kolom jumlah
                    ->orWhere('jumlah', 'like', '%' . $this->katakunci . '%')
                    // Cari di kolom tanggal
                    ->orWhere('tanggal', 'like', '%' . $this->katakunci . '%');
            });
        }

        // Terapkan pengurutan
        $query->orderBy($this->sortColoumn, $this->sortDirection);

        // Dapatkan data dengan paginasi
        $data = $query->paginate(5);

        // Dapatkan semua produk untuk dropdown di form
        $produk = ModelsProduk::all();

        return view('livewire.penjualan', [
            'datapenjualan' => $data,
            'produk' => $produk // Mengirimkan daftar produk ke view
        ])->title('Penjualan');
    }
}
