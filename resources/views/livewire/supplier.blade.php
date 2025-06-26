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
            @if ($supplier_selected_id)
                <div class="m-3">
                    <a wire:click="delete_confirmation('')" class="btn btn-danger btn-sm mb-3" data-bs-toggle="modal"
                        data-bs-target="#modalhapus">Del {{ count($supplier_selected_id) }} Data</a>
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
                                    <label for="supplier1" class="form-label">Nama supplier</label>
                                    <input type="text" class="form-control" id="supplier`"
                                        placeholder="Nama supplier" wire:model="nama_supplier">
                                </div>
                                <div class="mb-3">
                                    <label for="supplier1" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="supplier`"
                                        placeholder="Alamat Supplier" wire:model="alamat">
                                </div>
                                <div class="mb-3">
                                    <label for="supplier1" class="form-label">Telepon</label>
                                    <input type="text" class="form-control" id="supplier`"
                                        placeholder="Nomer Telepon Supplier" wire:model="telepon">
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
                {{ $datasupplier->links() }}
                <table class="table table-bordered table-sortable">
                    <thead>
                        <tr>
                            <th></th>
                            <th scope="col">No</th>
                            <th scope="col"
                                class="sort @if ($sortColoumn == 'nama_supplier') {{ $sortDirection }} @endif"
                                wire:click="sort('nama_supplier')">Nama supplier</th>
                            <th scope="col"
                                class="sort @if ($sortColoumn == 'alamat') {{ $sortDirection }} @endif"
                                wire:click="sort('alamat')">Alamat</th>
                            <th scope="col"
                                class="sort @if ($sortColoumn == 'telepon') {{ $sortDirection }} @endif"
                                wire:click="sort('telepon')">Telepon</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datasupplier as $key => $value)
                            <tr>
                                <td>
                                    <input type="checkbox" wire:model.live="supplier_selected_id"
                                        wire:key="{{ $value->id }}" value="{{ $value->id }}">
                                </td>
                                <td>{{ $datasupplier->firstitem() + $key }}</td>
                                <td>{{ $value->nama_supplier }}</td>
                                <td>{{ $value->alamat }}</td>
                                <td>0{{ $value->telepon }}</td>
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
                {{ $datasupplier->links() }}
            </div>
        </div>
    </div>
</div>
