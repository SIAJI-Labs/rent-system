@extends('layouts.adm.app', [
    'wsecond_title' => 'Kostumer: Detail Data',
    'sidebar_menu' => 'customer',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Kostumer: Detail Data',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Kostumer',
                'is_active' => false,
                'url' => route('adm.customer.index')
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
    {{-- Lightcase --}}
    @include('layouts.adm.partials.plugins.lightcase-css')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Kustomer</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.customer.index') }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Daftar Kustomer">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>
            <a href="{{ route('adm.customer.edit', $data->uuid) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit Data Kustomer">
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
                <th>Identitas</th>
                <td>{{ !empty($data->identity_type) ? $data->identity_type : '?' }} - {{ $data->identity_number ?? '-' }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $data->address }}</td>
            </tr>
        </table>

        <div class="card mt-4 mb-0">
            <div class="card-header">
                <h3 class="card-title">Daftar Kontak</h3>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped table-bordered datatable" id="customer_contact-table">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Data</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @if($data->customerContact()->exists())
                        <tbody>
                            @foreach ($data->customerContact()->orderBy('type', 'asc')->get() as $item)
                                <tr>
                                    <th>{{ ucwords($item->type) }}</th>
                                    <th>
                                        @if ($item->type != 'phone' && $item->type != 'mobile')
                                            {{-- Link --}}
                                            <a href="{{ $item->value }}" target="_blank">{{ $item->value }}</a>
                                        @else
                                            {{-- Phone / Mobile --}}
                                            <a href="{{ 'https://wa.me/62'.$item->value }}" target="_blank" class="btn btn-sm btn-success"><i class="fab fa-whatsapp mr-2"></i>{{ '+62'.$item->value }}</a>
                                            <a href="{{ 'tel:62'.$item->value }}" target="_blank">{{ '+62'.$item->value }}</a>
                                        @endif
                                    </th>
                                    <th>-</th>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="button" class="btn btn-sm btn-secondary" onclick="reloadData($(this))">
                    <i class="fas fa-sync-alt mr-1"></i> Muat Ulang</button>
            </div>
            <!-- /.card-footer-->
        </div>

        <div class="card mt-4 mb-0">
            <div class="card-header">
                <h3 class="card-title">Daftar Jaminan</h3>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped table-bordered datatable" id="customer_mortgage-table">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Data</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @if($data->customerMortgage()->exists())
                        <tbody>
                            @foreach ($data->customerMortgage()->orderBy('type', 'asc')->get() as $item)
                                <tr>
                                    <th>{{ ucwords($item->type) }}</th>
                                    <th>
                                        @if(!empty($item->value))
                                        <span>{{ $item->value }}</span>
                                        <hr class="my-1"/>
                                        @endif

                                        <a href="{{ route('adm.protected.images', ['customer', saEncryption($item->pict, false)]) }}" data-rel="lightcase" class="btn btn-xs btn-secondary"><i class="fas fa-eye mr-2"></i>Preview</a>
                                    </th>
                                    <th>-</th>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
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
    {{-- Lightcase --}}
    @include('layouts.adm.partials.plugins.lightcase-js')
@endsection

@section('js_inline')
<script>
    $.fn.dataTable.moment( 'dddd, MMMM Do, YYYY' );
    
    $(document).ready((e) => {
        $('a[data-rel^=lightcase]').lightcase();
        $('body').on('click', 'a[data-rel^=lightcase]', function(e) {
            var href = $(this).attr('href');
            lightcase.start({
                href: href
            });
            e.preventDefault();
        });

        $(".datatable").DataTable();
    });

    function reloadData(el){
        location.reload();
    }
</script>
@endsection