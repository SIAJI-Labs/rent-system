@extends('layouts.adm.app', [
    'wsecond_title' => 'Kategori',
    'sidebar_menu' => 'category',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Kategori',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Kategori',
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
        <h3 class="card-title">Daftar Kategori</h3>

        @can('category-create')
            <div class="card-tools btn-group">
                <a href="{{ route('adm.product.category.create') }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Tambah Baru">
                    <i class="fas fa-plus mr-1"></i> Tambah Baru
                </a>
            </div>
        @endcan
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered" id="category-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Gambar</th>
                    <th>Product</th>
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

        $("#category-table").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('adm.json.datatable.product.category.all') }}",
                type: "GET",
            },
            success: (result) => {
                console.log(result);
            },
            columns: [
                { "data": "name" },
                { "data": "pict" },
                { "data": "product_count" },
                { "data": null },
            ],
            columnDefs: [
                {
                    "targets": "_all",
                    "className": "align-middle"
                }, {
                    "targets": 1,
                    "searchable": false,
                    "orderable": false,
                    "render": (row, type, data) => {
                        if(row){
                            return `
                                <a href="{{ asset('images/category') }}/${row}" data-lightbox="${row}" class="btn btn-sm btn-primary">
                                    Preview
                                </a>
                            `;
                        }

                        return '-';
                    }
                }, {
                    "targets": 3,
                    "searchable": false,
                    "orderable": false,
                    "render": (row, type, data) => {
                        let extra = '';
                        @can('category-edit')
                            extra = `<a href="{{ route('adm.product.category.index') }}/${data.uuid}/edit" class="btn btn-sm btn-warning btn-action">
                                    <i class="far fa-edit mr-1"></i> Edit
                                </a>`;
                        @endcan

                        return `
                            <div class="btn-group">
                                <a href="{{ route('adm.product.category.index') }}/${data.uuid}" class="btn btn-sm btn-primary btn-action">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                ${extra}
                            </div>
                        `;
                    }
                }
            ]
        });
    });

    function reloadData(el){
        $("#category-table").DataTable().ajax.reload();
    }
</script>
@endsection