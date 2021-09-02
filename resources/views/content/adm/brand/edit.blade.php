@extends('layouts.adm.app', [
    'wsecond_title' => 'Merek: Edit Data',
    'sidebar_menu' => 'brand',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Merek: Edit Data',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Merek',
                'is_active' => false,
                'url' => route('adm.product.brand.index')
            ], [
                'title' => 'Edit Data',
                'is_active' => true,
                'url' => null
            ],
        ]
    ]
])

@section('content')
<form class="card" method="POST" action="{{ route('adm.product.brand.update', $data->uuid) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card-header">
        <h3 class="card-title">Merek: Edit Data</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.product.brand.index') }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Daftar Merek">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Merek" name="name" id="input-name" value="{{ $data->name }}" required>
            @error('name')
            <span class="text-invalid">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <div class="btn-group float-right">
            <button type="button" onclick="formReset()" class="btn btn-sm btn-danger">Reset</button>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
        </div>
        <div class="clearfix"></div>
    </div>
    <!-- /.card-footer-->
</form>
@endsection