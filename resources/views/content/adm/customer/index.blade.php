@extends('layouts.adm.app', [
    'wsecond_title' => 'Kostumer',
    'sidebar_menu' => 'customer',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Kostumer',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Kostumer',
                'is_active' => true,
                'url' => null
            ],
        ]
    ]
])

@section('css_plugins')
    {{-- Datatable --}}
    @include('layouts.adm.partials.plugins.datatable-css')
    {{-- Lightcase --}}
    @include('layouts.adm.partials.plugins.lightcase-css')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kostumer</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.customer.create') }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Tambah Baru">
                <i class="fas fa-plus mr-1"></i> Tambah Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered" id="customer-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kontak</th>
                    <th>Jaminan</th>
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
    {{-- Lightcase --}}
    @include('layouts.adm.partials.plugins.lightcase-js')
@endsection

@section('js_inline')
<script>
    $.fn.dataTable.moment( 'dddd, MMMM Do, YYYY' );
    
    $(document).ready((e) => {
        $("#customer-table").DataTable({
            order: [0, 'asc'],
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('adm.json.datatable.customer.all') }}",
                type: "GET",
            },
            success: (result) => {
                console.log(result);
            },
            columns: [
                { "data": "name" },
                { "data": "customer_contact_count" },
                { "data": "customer_mortgage_count" },
                { "data": null },
            ],
            columnDefs: [
                {
                    "targets": "_all",
                    "className": "align-middle"
                }, {
                    "targets": 3,
                    "searchable": false,
                    "orderable": false,
                    "render": (row, type, data) => {
                        return `
                            <div class="btn-group">
                                <a href="{{ route('adm.customer.index') }}/${data.uuid}" class="btn btn-sm btn-primary btn-action">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                <a href="{{ route('adm.customer.index') }}/${data.uuid}/edit" class="btn btn-sm btn-warning btn-action">
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
        $("#customer-table").DataTable().ajax.reload();
    }
</script>
@endsection