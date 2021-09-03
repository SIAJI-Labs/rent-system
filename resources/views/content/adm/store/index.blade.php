@extends('layouts.adm.app', [
    'wsecond_title' => 'Daftar Toko',
    'sidebar_menu' => 'store',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Daftar Toko',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Toko',
                'is_active' => true,
                'url' => false
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
        <h3 class="card-title">Daftar Toko</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.store.create') }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Tambah Baru">
                <i class="fas fa-plus mr-1"></i> Tambah Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered" id="store-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jumlah Staff</th>
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
        $("#store-table").DataTable({
            order: [2, 'asc'],
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('adm.json.datatable.store.all') }}",
                type: "GET",
            },
            success: (result) => {
                console.log(result);
            },
            columns: [
                { "data": "name" },
                { "data": null },
                { "data": null },
            ],
            columnDefs: [
                {
                    "targets": "_all",
                    "className": "align-middle"
                }, {
                    "targets": 0,
                    "searchable": false,
                    "render": (row, type, data) => {
                        return `[${data.invoice_prefix}] ${row}`;
                    }
                }, {
                    "targets": 1,
                    "searchable": false,
                    "render": (row, type, data) => {
                        return `-`;
                    }
                }, {
                    "targets": 2,
                    "searchable": false,
                    "orderable": false,
                    "render": (row, type, data) => {
                        return `
                            <div class="btn-group">
                                <a href="{{ route('adm.store.index') }}/${data.uuid}" class="btn btn-sm btn-primary btn-action">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                <a href="{{ route('adm.store.index') }}/${data.uuid}/edit" class="btn btn-sm btn-warning btn-action">
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
        $("#store-table").DataTable().ajax.reload();
    }
</script>
@endsection