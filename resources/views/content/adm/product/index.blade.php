@extends('layouts.adm.app', [
    'wsecond_title' => 'Produk',
    'sidebar_menu' => 'product',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Produk',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Produk',
                'is_active' => true,
                'url' => null
            ],
        ]
    ]
])

@section('css_plugins')
    {{-- Datatable --}}
    @include('layouts.adm.partials.plugins.datatable-css')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Produk</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.product.create') }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Tambah Baru">
                <i class="fas fa-plus mr-1"></i> Tambah Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered" id="product-table">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Brand</th>
                    <th>Nama</th>
                    <th>Biaya Sewa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="button" class="btn btn-sm btn-secondary" onclick="reloadData($(this))">
            <i class="fas fa-sync-alt mr-1"></i> Muat Ulang</button>
    </div>
    <!-- /.card-footer-->
</div>
@endsection

@section('js_plugins')
    {{-- Datatable --}}
    @include('layouts.adm.partials.plugins.datatable-js')
@endsection

@section('js_inline')
<script>
    $.fn.dataTable.moment( 'dddd, MMMM Do, YYYY' );
    
    $(document).ready((e) => {
        $("#product-table").DataTable({
            order: [2, 'asc'],
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('adm.json.datatable.product.all') }}",
                type: "GET",
            },
            success: (result) => {
                console.log(result);
            },
            columns: [
                { "data": null },
                { "data": null },
                { "data": "name" },
                { "data": "price" },
                { "data": null },
            ],
            columnDefs: [
                {
                    "targets": "_all",
                    "className": "align-middle"
                }, {
                    "targets": [0, 1],
                    "searchable": false,
                    "orderable": false,
                }, {
                    "targets": 0,
                    "render": (row, type, data) => {
                        return !jQuery.isEmptyObject(data.category) ? `<a href="{{ route('adm.product.category.index') }}/${data.category.uuid}">${row}</a>` : '-';
                    }
                }, {
                    "targets": 1,
                    "render": (row, type, data) => {
                        return !jQuery.isEmptyObject(data.brand) ? `<a href="{{ route('adm.product.brand.index') }}/${data.brand.uuid}">${row}</a>` : '-';
                    }
                }, {
                    "targets": 3,
                    "searchable": false,
                    "render": (row, type, data) => {
                        return formatRupiah(row);
                    }
                }, {
                    "targets": 4,
                    "searchable": false,
                    "orderable": false,
                    "render": (row, type, data) => {
                        return `
                            <div class="btn-group">
                                <a href="{{ route('adm.product.index') }}/${data.uuid}" class="btn btn-sm btn-primary btn-action">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                <a href="{{ route('adm.product.index') }}/${data.uuid}/edit" class="btn btn-sm btn-warning btn-action">
                                    <i class="far fa-edit mr-1"></i> Edit
                                </a>
                            </div>
                        `;
                    }
                }
            ]
        });
    });

    function reloadData(el){
        $("#product-table").DataTable().ajax.reload();
    }
</script>
@endsection