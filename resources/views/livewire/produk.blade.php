<div>
    <div class="container mt-2">
        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="pt-3">
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if (session()->has('message'))
                    <div class="pt-3">
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    </div>
                @endif
            </div>
            <div class="m-3">
                <a class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah Data +</a>
            </div>
            @if ($produk_selected_id)
                <div class="m-3">
                    <a wire:click="delete_confirmation('')" class="btn btn-danger btn-sm mb-3" data-bs-toggle="modal"
                        data-bs-target="#modalhapus">Del {{ count($produk_selected_id) }} Data</a>
                </div>
            @endif
            <div class="mx-3">
                <input type="text" class="form-control mb-3 w-25" placeholder="Search..."
                    wire:model.live="katakunci">
            </div>
            <div wire:ignore.self class="modal fade" id="modalhapus" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Delete</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Yakin akan menghapus data ini?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="clear()"
                                data-bs-dismiss="modal">Tidak</button>
                            <button type="button" class="btn btn-primary" wire:click="delete()"
                                data-bs-dismiss="modal">Ya</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">
                                {{ $ngeditdata == false ? 'Tambah' : 'Edit' }} {{ $title }}</h1>
                        </div>
                        <form>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nampro" class="form-label">Nama produk</label>
                                    <input type="text" class="form-control" id="nampro" placeholder="Nama produk"
                                        wire:model="nama_produk">
                                </div>
                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control" id="harga" placeholder="Harga produk"
                                        wire:model="harga">
                                </div>
                                <div class="mb-3">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input type="number" class="form-control" id="stok" placeholder="Stok produk"
                                        wire:model="stok">
                                </div>
                                <div class="mb-3">
                                    <label for="kategori" class="form-label">Kategori</label>
                                    <select class="form-select" aria-label="Default select example"
                                        wire:model="kategori_id">
                                        <option selected>Pilih Kategori</option>
                                        @foreach ($kategori as $opsik)
                                            <option value="{{ $opsik->id }}">{{ $opsik->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="produk1" class="form-label">Supplier</label>
                                    <select class="form-select" aria-label="Default select example"
                                        wire:model="supplier_id">
                                        <option selected>Pilih Supplier</option>
                                        @foreach ($supplier as $opsis)
                                            <option value="{{ $opsis->id }}">{{ $opsis->nama_supplier }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="clear()"
                                    data-bs-dismiss="modal">Batal</button>
                                @if ($ngeditdata == false)
                                    <button type="button" class="btn btn-primary" name="submit"
                                        wire:click="store()" data-bs-dismiss="modal">Simpan</button>
                                @else
                                    <button type="button" class="btn btn-primary" name="submit"
                                        wire:click="update()" data-bs-dismiss="modal">Edit</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {{ $dataproduk->links() }}
                <table class="table table-bordered table-sortable">
                    <thead>
                        <tr>
                            <th></th>
                            <th scope="col">No</th>
                            <th scope="col"
                                class="sort @if ($sortColoumn == 'nama_produk') {{ $sortDirection }} @endif"
                                wire:click="sort('nama_produk')">Nama produk</th>
                            <th scope="col"
                                class="sort @if ($sortColoumn == 'harga') {{ $sortDirection }} @endif"
                                wire:click="sort('harga')">Harga</th>
                            <th scope="col"
                                class="sort @if ($sortColoumn == 'stok') {{ $sortDirection }} @endif"
                                wire:click="sort('stok')">Stok</th>
                            <th scope="col"
                                class="sort @if ($sortColoumn == 'nama_kategori') {{ $sortDirection }} @endif"
                                wire:click="sort('nama_kategori')">Kategori</th>
                            <th scope="col"
                                class="sort @if ($sortColoumn == 'nama_kategori') {{ $sortDirection }} @endif"
                                wire:click="sort('nama_kategori')">Supplier</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataproduk as $key => $value)
                            <tr>
                                <td>
                                    <input type="checkbox" wire:model.live="produk_selected_id"
                                        wire:key="{{ $value->id }}" value="{{ $value->id }}">
                                </td>
                                <td>{{ $dataproduk->firstitem() + $key }}</td>
                                <td>{{ $value->nama_produk }}</td>
                                <td>Rp {{ number_format($value->harga, 0, ',', '.') }}</td>
                                <td>{{ $value->stok }}</td>
                                <td>{{ $value->kategoris->nama_kategori ?? '-' }}</td>
                                <td>{{ $value->suppliers->nama_supplier ?? '-' }}</td>
                                <td>
                                    <a wire:click="edit({{ $value->id }})" class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#exampleModal">Edit</a>
                                    <a wire:click="delete_confirmation({{ $value->id }})"
                                        class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalhapus">Del</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $dataproduk->links() }}
            </div>
        </div>
    </div>
</div>
