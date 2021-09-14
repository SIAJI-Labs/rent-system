@extends('layouts.adm.app', [
    'wsecond_title' => 'Terdapat Kesalahan',
    'sidebar_menu' => null,
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Kesalahan!',
        'header_breadcrumb' => [
            [
                'title' => 'Kesalahan!',
                'is_active' => false,
                'url' => route('adm.index')
            ],
        ]
    ]
])

@section('content')
    <div class="d-flex my-4 justify-content-center">
        <div class="d-block text-center">
            <img src="{{ asset('images/undraw_bug_fixing_oc7a.svg') }}" alt="Something Went Wrong" width="45%">

            <h3 class="title mt-2 text-uppercase">Ada suatu kesalahan!</h3>
            <span class="error-message">{{ $exception ?? 'Ada kesalahan yang tidak dapat sistem atasi, laporkan kepada Admin jika kesalahan ini terjadi berulang' }}</span>

            <div class="btn-group d-block mt-4">
                <a href="{{ route('adm.index') }}" class="btn btn-secondary btn-sm">Kembali ke Dashboard</a>
                <a href="{{ url()->previous() }}" class="btn btn-info btn-sm">Kembali ke Halaman Sebelumnya</a>
            </div>
        </div>
    </div>
@endsection