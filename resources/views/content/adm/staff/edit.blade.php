@extends('layouts.adm.app', [
    'wsecond_title' => 'Staff: Edit Data',
    'sidebar_menu' => 'staff',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Staff: Edit Data',
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
<form class="card" method="POST" action="{{ route('adm.staff.update', $data->uuid) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card-header">
        <h3 class="card-title">Serial Number: Edit Data</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.staff.index') }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Daftar Staff">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Toko</label>
            <select class="form-control @error('store_id') is-invalid @enderror" id="input-store_id" name="store_id" style="width: 100% !important;">
                @if ($data->store()->exists())
                    <option value="{{ $data->store->id }}" selected>{{ $data->store->name }}</option>
                @endif
            </select>
            @error('store_id')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Nama</label>

            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="input-name" placeholder="Nama Staff" value="{{ $data->name }}">
            @error('name')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Username</label>

            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="input-username" placeholder="Username Staff" value="{{ $data->username }}">
            @error('username')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Email</label>

            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="input-email" placeholder="Email Staff" value="{{ $data->email }}">
            @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" name="is_active" id="input-is_active" {{ $data->is_active ? 'checked' : '' }}>
                <label class="custom-control-label" for="input-is_active">{{ $data->is_active ? 'Staff Aktif' : 'Staff Tidak Aktif' }}</label>
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
    $("#input-is_active").change((e) => {
        let formGroup = $(e.target).closest('.form-group');
        let label = $(formGroup).find('label');

        let text = 'Staff Tidak Aktif';
        if($(e.target).is(':checked')){
            text = 'Staff Aktif';
        }
        $(label).text(text);
    });
</script>
@endsection