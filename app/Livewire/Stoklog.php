<?php

namespace App\Livewire;

use App\Models\Produk as ModelsProduk;
use App\Models\Stok_log as ModelsStoklog;
use Livewire\Component;
use Livewire\WithPagination;

class Stoklog extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $title = 'Stok Log';
    public $produk_id;
    public $jenis;
    public $jumlah;
    public $tanggal;
    public $ngeditdata = false;
    public $stoklog_id;
    public $stoklog_selected_id = [];
    public $katakunci;
    public $sortColoumn = 'nama_produk';
    public $sortDirection = 'asc';

    public function clear()
    {
        $this->produk_id = '';
        $this->jenis = '';
        $this->jumlah = '';
        $this->tanggal = '';

        $this->ngeditdata = false;
        $this->stoklog_id = '';
        $this->stoklog_selected_id = [];
    }

    public function store()
    {
        $rules = [
            'produk_id' => 'required',
            'jenis' => 'required',
            'jumlah' => 'required',
            'tanggal' => 'required',
        ];
        $pesan = [
            'produk_id.required' => 'Nama wajib diisi',
            'jenis.required' => 'Jenis wajib diisi',
            'jumlah.required' => 'Jumlah wajib diisi',
            'tanggal.required' => 'Tanggal wajib diisi',
        ];
        $validated = $this->validate($rules, $pesan);
        $produk = ModelsProduk::find($validated['produk_id']);

        if ($validated['jenis'] == 'masuk') {
            // Kurangi stok produk
            $produk->stok += $validated['jumlah'];
            $produk->save(); // Simpan perubahan stok

            // Buat entri penjualan
            ModelsStoklog::create($validated);

            session()->flash('message', 'Data stok log berhasil ditambahkan dan stok produk diperbarui!');
            $this->clear(); // Pastikan metode clear() Anda ada dan berfungsi
        } else if ($produk && $produk->stok >= $validated['jumlah']) {
            // Kurangi stok produk
            $produk->stok -= $validated['jumlah'];
            $produk->save(); // Simpan perubahan stok

            // Buat entri penjualan
            ModelsStoklog::create($validated);

            session()->flash('message', 'Data stok log berhasil ditambahkan dan stok produk diperbarui!');
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
        $data = ModelsStoklog::find($id);
        $this->produk_id = $data->produk_id;
        $this->jumlah = $data->jumlah;
        $this->tanggal = $data->tanggal;

        $this->ngeditdata = true;
        $this->stoklog_id = $id;
    }

    public function update()
    {
        $rules = [
            'produk_id' => 'required',
            'jenis' => 'required',
            'jumlah' => 'required',
            'tanggal' => 'required',
        ];
        $pesan = [
            'produk_id.required' => 'Nama wajib diisi',
            'jenis.required' => 'Jenis wajib diisi',
            'jumlah.required' => 'Jumlah wajib diisi',
            'tanggal.required' => 'Tanggal wajib diisi',
        ];
        $validated = $this->validate($rules, $pesan);
        $data = ModelsStoklog::find($this->stoklog_id);
        $data->update($validated);
        session()->flash('message', 'Data berhasil diedit');

        $this->clear();
    }

    public function delete()
    {
        if ($this->stoklog_id != null) {
            $id = $this->stoklog_id;
            ModelsStoklog::find($id)->delete();
        }
        if (count($this->stoklog_selected_id)) {
            for ($x = 0; $x < count($this->stoklog_selected_id); $x++) {
                ModelsStoklog::find($this->stoklog_selected_id[$x])->delete();
            }
        }
        session()->flash('message', 'Data berhasil dihapus');
        $this->clear();
    }

    public function delete_confirmation($id)
    {
        if ($id != null) {
            $this->stoklog_id = $id;
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
        $query = ModelsStoklog::with('produks'); // Ini akan memuat semua produk yang terkait

        // Jika kolom pengurutan adalah 'nama_produk', lakukan JOIN dan select kolom
        if ($this->sortColoumn == 'nama_produk') {
            $query->join('produks', 'stok_logs.produk_id', '=', 'produks.id')
                ->select('stok_logs.*', 'produks.nama_produk as product_name_for_sort'); // Alias untuk nama_produk
            // Mengubah kolom pengurutan menjadi alias yang baru dibuat
        }

        // Filter berdasarkan kata kunci
        if ($this->katakunci != null) {
            $query->where(function ($q) {
                // Cari di nama_produk melalui relasi
                $q->whereHas('produks', function ($queryProduk) {
                    $queryProduk->where('nama_produk', 'like', '%' . $this->katakunci . '%');
                })
                    ->orWhere('jenis', 'like', '%' . $this->katakunci . '%')
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

        return view('livewire.stoklog', [
            'datastoklog' => $data,
            'produk' => $produk // Mengirimkan daftar produk ke view
        ])->title('Stok Log');
    }
}
