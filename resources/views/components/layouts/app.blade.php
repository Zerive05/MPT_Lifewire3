<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <style>
        .table-sortable>thead>tr>th.sort {
            cursor: pointer;
            position: relative;
        }

        .table-sortable>thead>tr>th.sort:after,
        .table-sortable>thead>tr>th.sort:after,
        .table-sortable>thead>tr>th.sort:after {
            content: ' ';
            position: absolute;
            height: 0;
            width: 0;
            right: 10px;
            top: 16px;
        }

        .table-sortable>thead>tr>th.sort:after {
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #ccc;
            border-bottom: 0px solid transparent;
        }

        .table-sortable>thead>tr>th:hover:after {
            border-top: 5px solid #888;
        }

        .table-sortable>thead>tr>th.sort.asc:after {
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 0px solid transparent;
            border-bottom: 5px solid #333;
        }

        .table-sortable>thead>tr>th.sort.asc:hover:after {
            border-bottom: 5px solid #888;
        }

        .table-sortable>thead>tr>th.sort.desc:after {
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #333;
            border-bottom: 5px solid transparent;
        }
    </style>
    <title>{{ $title ?? 'Page Title' }}</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-dark border-bottom border-body" data-bs-theme="dark">
        <div class="container-fluid">
            {{-- <a class="navbar-brand" href="/">Logo Web</a> --}}
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link @if ($title == 'Dashboard') active @endif" aria-current="page"
                            href="/">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($title == 'Produk') active @endif" aria-current="page"
                            href="/produk">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($title == 'Kategori') active @endif" aria-current="page"
                            href="/kategori">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($title == 'Supplier') active @endif" aria-current="page"
                            href="/supplier">Supplier</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($title == 'Penjualan') active @endif" aria-current="page"
                            href="/penjualan">Penjualan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($title == 'Stok Log') active @endif" aria-current="page"
                            href="/stoklog">Stok Log</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    {{ $slot }}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
</body>

</html>
