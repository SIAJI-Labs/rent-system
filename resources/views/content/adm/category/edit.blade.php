@extends('layouts.adm.app', [
    'wsecond_title' => 'Kategori: Edit Data',
    'sidebar_menu' => 'category',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Kategori: Edit Data',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Kategori',
                'is_active' => false,
                'url' => route('adm.product.category.index')
            ], [
                'title' => 'Edit Data',
                'is_active' => true,
                'url' => null
            ],
        ]
    ]
])

@section('content')
<form class="card" method="POST" action="{{ route('adm.product.category.update', $data->uuid) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card-header">
        <h3 class="card-title">Kategori: Edit Data</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.product.category.index') }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Daftar Kategori">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Kategori" name="name" id="input-name" value="{{ $data->name }}" required>
            @error('name')
            <span class="text-invalid">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea class="ckeditor d-none @error('description') is-invalid @enderror" name="description" id="input-description" placeholder="Deskripsi mengenai kategori akan berada disini...">{!! $data->description !!}</textarea>
            @error('description')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Gambar</label>
            <div class="preview-container">
                <div class="custom-file">
                    <input type="file" class="custom-file-input @error('pict') is-invalid @enderror" name="pict" id="input-pict" onchange="generateCustomPreview($(this))" accept=".jpg,.jpeg,.png" required>
                    <label class="custom-file-label" for="input-pict">Choose file</label>

                    @error('pict')
                    <div class='invalid-feedback'>{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Saran: Gunakan gambar dengan ekstensi PNG, Ukuran maksimal gambar adalah 500kb.</small>
                </div>
                <div class="row mt-2">
                    <div class="col-lg-4 col-12">
                        <div class="img-preview mb-2">
                            <button type="button" class="btn btn-sm btn-danger d-block mb-2 mx-auto btn-preview_remove" onclick="removeCustomPreview($(this), '{{ asset('images/category'.'/'.$data->pict) }}')" disabled>Reset Preview</button>
                            <img class="img-responsive" width="100%;" style="padding:.25rem;background:#eee;display:block;" src="{{ asset('images/category'.'/'.$data->pict) }}">
                        </div>
                    </div>
                </div>
            </div>
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
    {{-- Bootstrap Custom File Input --}}
    @include('layouts.adm.partials.plugins.bsfile-input-js')
@endsection

@section('js_inline')
<script>
    $(document).ready((e) => {
        bsCustomFileInput.init();
    });

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
                    // 'insertTable',
                    // 'mediaEmbed',
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