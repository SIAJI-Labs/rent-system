@extends('layouts.adm.app', [
    'wsecond_title' => 'Staff',
    'sidebar_menu' => 'staff',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Staff',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Staff',
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
        <h3 class="card-title">Daftar Staff</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.staff.create') }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Tambah Baru">
                <i class="fas fa-plus mr-1"></i> Tambah Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered" id="staff-table">
            <thead>
                <tr>
                    <th>Toko</th>
                    <th>Nama</th>
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
@endsection

@section('js_plugins')
    {{-- Datatable --}}
    @include('layouts.adm.partials.plugins.datatable-js')
@endsection

@section('js_inline')
<script>
    $.fn.dataTable.moment( 'dddd, MMMM Do, YYYY' );
    
    $(document).ready((e) => {
        $("#staff-table").DataTable({
            order: [1, 'asc'],
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('adm.json.datatable.staff.all') }}",
                type: "GET",
            },
            success: (result) => {
                console.log(result);
            },
            columns: [
                { "data": null },
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
                    "orderable": false,
                    "render": (row, type, data) => {
                        if(!(jQuery.isEmptyObject(data.store))){
                            return `<a href="{{ route('adm.store.index') }}/${data.store.uuids}">${data.store.name}</a>`;
                        }

                        return '-';
                    }
                }, {
                    "targets": 2,
                    "searchable": false,
                    "orderable": false,
                    "render": (row, type, data) => {
                        return `<span class="badge badge-${data.is_active ? 'primary' : 'danger'}">${data.is_active ? 'Aktif' : 'Tidak Aktif'}</span>`;
                    }
                }, {
                    "targets": 3,
                    "searchable": false,
                    "orderable": false,
                    "render": (row, type, data) => {
                        return `
                            <div class="btn-group">
                                <a href="{{ route('adm.staff.index') }}/${data.uuid}" class="btn btn-sm btn-primary btn-action">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                <a href="{{ route('adm.staff.index') }}/${data.uuid}/edit" class="btn btn-sm btn-warning btn-action">
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
        $("#staff-table").DataTable().ajax.reload();
    }
</script>
@endsection