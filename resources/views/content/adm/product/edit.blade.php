@extends('layouts.adm.app', [
    'wsecond_title' => 'Produk: Edit Data',
    'sidebar_menu' => 'product',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Produk: Edit Data',
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
                'title' => 'Edit Data',
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
<form class="card" method="POST" action="{{ route('adm.product.update', $data->uuid) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card-header">
        <h3 class="card-title">Produk: Edit Data</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.product.index') }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Daftar Produk">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <div class="form-group col-12 col-lg-6 mb-lg-0">
                <label>Merek</label>
                <select class="form-control @error('brand_id') is-invalid @enderror" id="input-brand_id" name="brand_id" style="width: 100% !important;">
                    @if($data->brand()->exists())
                    <option value="{{ $data->brand->id }}" selected>{{ $data->brand->name }}</option>
                    @endif
                </select>
                @error('brand_id')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-12 col-lg-6 mb-lg-0">
                <label>Kategori</label>
                <select class="form-control @error('category_id') is-invalid @enderror" id="input-category_id" name="category_id" style="width: 100% !important;">
                    @if($data->category()->exists())
                    <option value="{{ $data->category->id }}" selected>{{ $data->category->name }}</option>
                    @endif
                </select>
                @error('category_id')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Produk" name="name" id="input-name" value="{{ $data->name }}" required>
            @error('name')
            <span class="text-invalid">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Biaya Sewa</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Rp</span>
                </div>
                <input data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'digits': '0', 'allowMinus': 'false'" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ $data->price ?? 0 }}" min="0" placeholder="Biaya Sewa" min="0" required>
            </div>
            @error('price')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea class="ckeditor d-none @error('description') is-invalid @enderror" name="description" id="input-description" placeholder="Deskripsi mengenai Produk akan berada disini...">{!! $data->description !!}</textarea>
            @error('description')
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
    {{-- Input Mask --}}
    @include('layouts.adm.partials.plugins.input-mask-js')
    {{-- Select2 --}}
    @include('layouts.adm.partials.plugins.select2-js')
@endsection

@section('js_inline')
<script>
    $(document).ready((e) => {
        $(":input").inputmask(undefined);

        let select2_query = {};
        $(".form-select2").select2({
            theme: 'bootstrap4',
            allowClear: true,
        });
        $("#input-brand_id").select2({
            placeholder: 'Cari Merek',
            theme: 'bootstrap4',
            allowClear: true,
            ajax: {
                url: "{{ route('adm.json.select2.product.brand.all') }}",
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

                    console.log(items);
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
        $("#input-category_id").select2({
            placeholder: 'Cari Kategori',
            theme: 'bootstrap4',
            allowClear: true,
            ajax: {
                url: "{{ route('adm.json.select2.product.category.all') }}",
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