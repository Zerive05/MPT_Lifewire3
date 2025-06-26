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
            @if ($stoklog_selected_id)
                <div class="m-3">
                    <a wire:click="delete_confirmation('')" class="btn btn-danger btn-sm mb-3" data-bs-toggle="modal"
                        data-bs-target="#modalhapus">Del {{ count($stoklog_selected_id) }} Data</a>
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
                                    <label for="produk1" class="form-label">Produk</label>
                                    <select class="form-select" aria-label="Default select example"
                                        wire:model="produk_id">
                                        <option selected>Pilih Supplier</option>
                                        @foreach ($produk as $opsip)
                                            <option value="{{ $opsip->id }}">{{ $opsip->nama_produk }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="produk1" class="form-label">Jenis</label>
                                    <select class="form-select" aria-label="Default select example"
                                        wire:model="jenis">
                                        <option selected>Pilih Jenis</option>
                                            <option value="masuk">Masuk</option>
                                            <option value="keluar">Keluar</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="stoklog1" class="form-label">Jumlah</label>
                                    <input type="number" class="form-control" id="stoklog`"
                                        placeholder="Jumlah stoklog" wire:model="jumlah">
                                </div>
                                <div class="mb-3">
                                    <label for="stoklog1" class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" id="stoklog`"
                                        placeholder="Tanggal stoklog" wire:model="tanggal">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="clear()"
                                    data-bs-dismiss="modal">Batal</button>
                                @if ($ngeditdata == false)
                                    <button type="button" class="btn btn-primary" name="submit" wire:click="store()"
                                        data-bs-dismiss="modal">Simpan</button>
                                @else
                                    <button type="button" class="btn btn-primary" name="submit" wire:click="update()"
                                        data-bs-dismiss="modal">Edit</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {{ $datastoklog->links() }}
                <table class="table table-bordered table-sortable">
                    <thead>
                        <tr>
                            <th></th>
                            <th scope="col">No</th>
                            <th scope="col"
                                class="sort @if ($sortColoumn == 'produk_id') {{ $sortDirection }} @endif"
                                wire:click="sort('produk_id')">Produk ID</th>
                            <th scope="col"
                                class="sort @if ($sortColoumn == 'nama_produk') {{ $sortDirection }} @endif"
                                wire:click="sort('nama_produk')">Nama Produk</th>
                            <th scope="col"
                                class="sort @if ($sortColoumn == 'jenis') {{ $sortDirection }} @endif"
                                wire:click="sort('jenis')">Jenis</th>
                            <th scope="col"
                                class="sort @if ($sortColoumn == 'jumlah') {{ $sortDirection }} @endif"
                                wire:click="sort('jumlah')">Jumlah</th>
                            <th scope="col"
                                class="sort @if ($sortColoumn == 'tanggal') {{ $sortDirection }} @endif"
                                wire:click="sort('tanggal')">Tanggal</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datastoklog as $key => $value)
                            <tr>
                                <td>
                                    <input type="checkbox" wire:model.live="stoklog_selected_id"
                                        wire:key="{{ $value->id }}" value="{{ $value->id }}">
                                </td>
                                <td>{{ $datastoklog->firstitem() + $key }}</td>
                                <td>{{ $value->produk_id }}</td>
                                <td>{{ $value->produks->nama_produk ?? '-' }}</td>
                                <td>{{ $value->jenis }}</td>
                                <td>{{ $value->jumlah }}</td>
                                <td>{{ $value->tanggal }}</td>
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
                {{ $datastoklog->links() }}
            </div>
        </div>
    </div>
</div>
