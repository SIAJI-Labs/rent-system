@extends('layouts.adm.app', [
    'wsecond_title' => 'Toko: Tambah Baru',
    'sidebar_menu' => 'store',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Toko: Tambah Baru',
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
                'title' => 'Tambah Baru',
                'is_active' => true,
                'url' => false
            ],
        ]
    ]
])

@section('content')
<form class="card" method="POST" action="{{ route('adm.store.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="card-header">
        <h3 class="card-title">Toko: Tambah Baru</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.store.index') }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Daftar Toko">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Toko" name="name" id="input-name" value="{{ old('name') }}" required>
            @error('name')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="input-address" placeholder="Alamat Toko">{!! old('address') !!}</textarea>
            @error('address')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group col-12 col-md-6">
                <label>Latitude</label>
                <input type="text" class="form-control @error('latitude') is-invalid @enderror" placeholder="Maps Latitude" name="latitude" id="input-latitude" value="{{ old('latitude') }}">
                @error('latitude')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-12 col-md-6">
                <label>Longitude</label>
                <input type="text" class="form-control @error('longitude') is-invalid @enderror" placeholder="Maps Longitude" name="longitude" id="input-longitude" value="{{ old('longitude') }}">
                @error('longitude')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Prefix Invoice</label>
            <input type="text" class="form-control @error('invoice_prefix') is-invalid @enderror" placeholder="Prefix Invoice" name="invoice_prefix" id="input-invoice_prefix" value="{{ old('invoice_prefix') }}" required>
            @error('invoice_prefix')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            
            <small class="text-muted d-block">*Karakter diijinkan: A-Z, Maksimal 6 karakter</small>
            <small class="text-muted d-block">**Digunakan untuk struktur invoice transaksi (Misal: INVC/{{ date("Ymd") }}/PREFIX/1630592268</small>
        </div>

        <div class="form-group">
            <label>Catatan</label>
            <textarea class="ckeditor d-none @error('note') is-invalid @enderror" name="note" id="input-note" placeholder="Catatan mengenai Toko akan berada disini...">{!! old('note') !!}</textarea>
            @error('note')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Chart Js Color</label>
            <input data-jscolor="{value:'{{ $data->chart_hex_color ?? '#FFFFF' }}'}" class="form-control @error('chart_color') is-invalid @enderror" name="chart_color" id="input-chart_color">
            
            @error('chart_color')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            <small class="text-muted d-block">*Warna untuk Chart Bar pada fitur Keuangan</small>
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

@section('js_plugins')
    {{-- CKEditor --}}
    @include('layouts.adm.partials.plugins.ckeditor-js')
    {{-- JS Color --}}
    @include('layouts.adm.partials.plugins.jscolor-js')
@endsection

@section('js_inline')
<script>
    // JS Color
    jscolor.presets.default = {
        closeButton:true
    };

    // CKEditor
    const watchdog = new CKSource.EditorWatchdog();
    window.watchdog = watchdog;
    watchdog.setCreator( ( element, config ) => {
        return CKSource.Editor
            .create( element, config )
            .then( editor => {
                return editor;
            });
    } );
    watchdog.setDestructor( editor => {
        return editor.destroy();
    });
    watchdog.on( 'error', handleError );
    watchdog
        .create( document.querySelector( '.ckeditor' ), {
            removePlugins: [ 'Title', 'Base64UploadAdapter' ],
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'undo',
                    'redo',
                    '|',
                    'alignment',
                    'numberedList',
                    'bulletedList',
                    'indent',
                    'outdent',
                    'fontSize',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    'strikethrough',
                    'subscript',
                    'superscript',
                    '|',
                    'fontBackgroundColor',
                    'fontColor',
                    'fontFamily',
                    'highlight',
                    '|',
                    'blockQuote',
                    // 'imageInsert',
                    'codeBlock',
                    'insertTable',
                    'mediaEmbed',
                    '|',
                    'findAndReplace',
                    'sourceEditing',
                    'removeFormat'
                ]
            },
            language: 'en',
            image: {
                toolbar: [
                    'toggleImageCaption',
                    'imageTextAlternative',
                    'imageStyle:inline',
                    'imageStyle:block',
                    'imageStyle:side',
                    'linkImage',
                    'ImageResize'
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells',
                    'tableCellProperties',
                    'tableProperties'
                ]
            },
        } )
        .catch( handleError );
			
    function handleError( error ){
        console.error( 'Oops, something went wrong!' );
        console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
        console.warn( 'Build id: 2m8xt6rsww6w-na9hyagnqswh' );
        console.error( error );
    }
</script>
@endsection