@extends('layouts.adm.app', [
    'wsecond_title' => 'Kategori: Detail Data',
    'sidebar_menu' => 'category',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Kategori: Detail Data',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Kategori',
                'is_active' => false,
                'url' => route('adm.product.category.index')
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
    {{-- Lightbox2 --}}
    @include('layouts.adm.partials.plugins.lightbox2-css')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Kategori</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.product.category.index') }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Daftar Kategori">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>
            <a href="{{ route('adm.product.category.edit', $data->uuid) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit Data Kategori">
                <i class="far fa-edit mr-1"></i> Edit
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <tr>
                <th>Nama</th>
                <td>{{ $data->name }}</td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{!! $data->description ?? '-' !!}</td>
            </tr>
            <tr>
                <th>Gambar</th>
                <td>
                    @if (!empty($data->pict))
                        <a href="{{ asset('images/category'.'/'.$data->pict) }}" data-lightbox="${row}" class="btn btn-sm btn-primary">Preview</a>
                    @else
                        <span>-</span>
                    @endif
                </td>
            </tr>
        </table>

        <div class="card mt-4 mb-0">
            <div class="card-header">
                <h3 class="card-title">Daftar Produk</h3>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped table-bordered" id="product-table">
                    <thead>
                        <tr>
                            <th>Merek</th>
                            <th>Nama</th>
                            <th>Biaya</th>
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
    {{-- Lightbox2 --}}
    @include('layouts.adm.partials.plugins.lightbox2-js')
@endsection

@section('js_inline')
<script>
    $.fn.dataTable.moment( 'dddd, MMMM Do, YYYY' );
    
    $(document).ready((e) => {
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true
        });

        $("#product-table").DataTable({
            order: [2, 'asc'],
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('adm.json.datatable.product.all') }}",
                type: "GET",
                data: function(d){
                    d.category_id = "{{ $data->id }}";
                }
            },
            success: (result) => {
                console.log(result);
            },
            columns: [
                { "data": "brand.name", "name": "brand.name" },
                { "data": "name" },
                { "data": "price" },
                { "data": null },
            ],
            columnDefs: [
                {
                    "targets": "_all",
                    "className": "align-middle"
                }, {
                    "targets": 0,
                    "render": (row, type, data) => {
                        return row ? `<a href="{{ route('adm.product.brand.index') }}/${data.brand.uuid}">${row}</a>` : '-';
                    }
                }, {
                    "targets": 2,
                    "searchable": false,
                    "render": (row, type, data) => {
                        return formatRupiah(row);
                    }
                }, {
                    "targets": 3,
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