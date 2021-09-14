@extends('layouts.adm.app', [
    'wsecond_title' => 'Produk: Detail Data',
    'sidebar_menu' => 'product',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Produk: Detail Data',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Produk',
                'is_active' => false,
                'url' => route('adm.product.index')
            ], [
                'title' => 'Detail Data',
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
        <h3 class="card-title">Detail Produk</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.product.index') }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Daftar Produk">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>
            <a href="{{ route('adm.product.edit', $data->uuid) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit Data Produk">
                <i class="far fa-edit mr-1"></i> Edit
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <tr>
                <th>Merek</th>
                <td>
                    @if($data->brand()->exists())
                    <a href="{{ route('adm.product.brand.show', $data->brand->uuid) }}">{{ $data->brand->name }}</a>
                    @else
                    <span>-</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>
                    @if($data->category()->exists())
                    <a href="{{ route('adm.product.category.show', $data->category->uuid) }}">{{ $data->category->name }}</a>
                    @else
                    <span>-</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Nama</th>
                <td>{{ $data->name }}</td>
            </tr>
            <tr>
                <th>Biaya Sewa</th>
                <td>{{ formatRupiah($data->price) }}</td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{!! $data->description ?? '-' !!}</td>
            </tr>
        </table>

        <div class="card mt-4 mb-0">
            <div class="card-header">
                <h3 class="card-title">Serial Number</h3>
        
                <div class="card-tools btn-group">
                    <a href="{{ route('adm.product.serial-number.create', $data->uuid) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Tambah Serial Number">
                        <i class="fas fa-plus mr-1"></i> Tambah Serial Number
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped table-bordered" id="serial_number-table">
                    <thead>
                        <tr>
                            <th>Toko</th>
                            <th>Serial Number</th>
                            <th>Status</th>
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
    </div>
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
        $("#serial_number-table").DataTable({
            order: [0, 'asc'],
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('adm.json.datatable.product.serial-number.all', $data->uuid) }}",
                type: "GET",
            },
            success: (result) => {
                console.log(result);
            },
            columns: [
                { "data": "store.name", "name": "store.name" },
                { "data": "serial_number" },
                { "data": "status" },
                { "data": null },
            ],
            columnDefs: [
                {
                    "targets": "_all",
                    "className": "align-middle"
                }, {
                    "targets": 2,
                    "render": (row, type, data) => {
                        return `<span class="badge badge-${row ? 'primary': 'danger'}">${row ? 'Aktif' : 'Tidak Aktif'}</span>`;
                    }
                }, {
                    "targets": 3,
                    "searchable": false,
                    "orderable": false,
                    "render": (row, type, data) => {
                        return `
                            <div class="btn-group">
                                <a href="javascript:void(0)" class="btn btn-sm btn-primary btn-action">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                <a href="{{ route('adm.product.serial-number.store', $data->uuid) }}/${data.uuid}/edit" class="btn btn-sm btn-warning btn-action">
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
        $("#serial_number-table").DataTable().ajax.reload();
    }
</script>
@endsection