@extends('layouts.adm.app', [
    'wsecond_title' => 'Produk Serial Number: Tambah Baru',
    'sidebar_menu' => 'product',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Produk Serial Number: Tambah Baru',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Produk',
                'is_active' => false,
                'url' => route('adm.product.index')
            ], [
                'title' => 'Serial Number: Tambah Baru',
                'is_active' => true,
                'url' => null
            ],
        ]
    ]
])

@section('css_plugins')
    {{-- Select2 --}}
    @include('layouts.adm.partials.plugins.select2-css')
@endsection

@section('content')
<form class="card" method="POST" action="{{ route('adm.product.serial-number.store', $product->uuid) }}" enctype="multipart/form-data">
    @csrf

    <div class="card-header">
        <h3 class="card-title">Serial Number: Tambah Baru</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.product.show', $product->uuid) }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Produk {{ $product->name }}">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Produk</label>
            <input type="text" class="form-control" value="{{ $product->name }}" readonly>
        </div>

        <div class="form-group">
            <label>Toko</label>
            <input type="hidden" name="old_store_id" value="{{ old('old_store_id') }}" id="input-old_store_id" readonly>
            <input type="hidden" name="old_store_id_text" value="{{ old('old_store_id_text') }}" id="input-old_store_id_text" readonly>

            <select class="form-control @error('store_id') is-invalid @enderror" id="input-store_id" name="store_id" style="width: 100% !important;">
                @if(old('old_store_id'))
                <option value="{{ old('old_store_id') }}" selected>{{ old('old_store_id_text') }}</option>
                @endif
            </select>
            @error('store_id')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Serial Number</label>
            <input type="text" class="form-control @error('serial_number') is-invalid @enderror" value="{{ old('serial_number') }}" id="input-serial_number" name="serial_number" placeholder="Serial Number dari Produk {{ $product->name }}">
            @error('serial_number')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Catatan</label>
            <textarea class="ckeditor d-none @error('note') is-invalid @enderror" name="note" id="input-note" placeholder="Catatan mengenai Serial Number pada Produk terkait akan berada disini...">{!! old('note') !!}</textarea>
            @error('note')
            <span class="invalid-feedback">{{ $message }}</span>
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

@section('js_plugins')
    {{-- CKEditor --}}
    @include('layouts.adm.partials.plugins.ckeditor-js')
    {{-- Select2 --}}
    @include('layouts.adm.partials.plugins.select2-js')
@endsection

@section('js_inline')
<script>
    $(document).ready((e) => {
        let select2_query = {};
        $(".form-select2").select2({
            theme: 'bootstrap4',
            allowClear: true,
        });
        $("#input-store_id").select2({
            placeholder: 'Cari Toko',
            theme: 'bootstrap4',
            allowClear: true,
            ajax: {
                url: "{{ route('adm.json.select2.store.all') }}",
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    }

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function (data, params) {
                    var items = $.map(data.data, function(obj){
                        obj.id = obj.id;
                        obj.text = obj.name;

                        return obj;
                    });
                    params.page = params.page || 1;

                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: items,
                        pagination: {
                            more: params.page < data.last_page
                        }
                    };
                },
            },
            templateResult: function (item) {
                // console.log(item);
                // No need to template the searching text
                if (item.loading) {
                    return item.text;
                }
                
                var term = select2_query.term || '';
                var $result = markMatch(item.text, term);

                return $result;
            },
            language: {
                searching: function (params) {
                    // Intercept the query as it is happening
                    select2_query = params;
                    
                    // Change this to be appropriate for your application
                    return 'Searching...';
                }
            }
        });
    });

    $("#input-store_id").change((e) => {
        let select_data = $(e.target).select2('data');
        let data_id = '';
        let data_text = '';

        if(!(jQuery.isEmptyObject(select_data))){
            data_id = select_data[0].id;
            data_text = select_data[0].text;
        } 

        $("#input-old_store_id").val(data_id);
        $("#input-old_store_id_text").val(data_text);
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