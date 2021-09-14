@extends('layouts.adm.app', [
    'wsecond_title' => 'Staff: Detail Data',
    'sidebar_menu' => 'staff',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Staff: Detail Data',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Staff',
                'is_active' => false,
                'url' => route('adm.staff.index')
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
        <h3 class="card-title">Detail Staff</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.staff.index') }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Daftar Staff">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>
            <a href="{{ route('adm.staff.edit', $data->uuid) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit Data Staff">
                <i class="far fa-edit mr-1"></i> Edit
            </a>
            <a href="{{ route('adm.permission.index', $data->uuid) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Ijin Akses Staff">
                <i class="fas fa-lock mr-1"></i> Ijin Akses
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <tr>
                <th>Toko</th>
                <td>
                    @if($data->store()->exists())
                        <a href="{{ route('adm.store.show', $data->store->uuid) }}">{{ $data->store->name }}</a>
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
                <th>Email</th>
                <td>
                    <a href="mailto:{{ $data->email }}">{{ $data->email }}</a>
                </td>
            </tr>
            <tr>
                <th>Username</th>
                <td>{{ $data->username }}</td>
            </tr>
            <tr>
                <th>Password</th>
                <td>{{ !empty($data->raw_password) ? saEncryption($data->raw_password, false) : '-' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="badge badge-{{ $data->is_active ? 'primary' : 'warning' }}">{{ $data->is_active ? 'Staff Aktif' : 'Staff Tidak Aktif' }}</span>
                </td>
            </tr>
        </table>

        <div class="card mt-4 mb-0">
            <div class="card-header">
                <h3 class="card-title">Log Transaksi</h3>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped table-bordered" id="transaction_log-table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Detail</th>
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
        
    });

    function reloadData(el){
        $("#transaction_log-table").DataTable().ajax.reload();
    }
</script>
@endsection