@extends('layouts.adm.app', [
    'wsecond_title' => 'Toko: Detail Data',
    'sidebar_menu' => 'store',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Toko: Detail Data',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Toko',
                'is_active' => false,
                'url' => route('adm.store.index')
            ], [
                'title' => 'Detail Data',
                'is_active' => true,
                'url' => false
            ],
        ]
    ]
])

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Toko: Detail Data</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.store.index') }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Daftar Toko">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>

            @can('store-edit')
                <a href="{{ route('adm.store.edit', $data->uuid) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit Data Toko">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered">
            <tr>
                <th>Nama</th>
                <td>{{ $data->name }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{!! $data->address ?? '-' !!}</td>
            </tr>
            <tr>
                <th>Catatan</th>
                <td>
                    {!! $data->note ?? '-' !!}
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection