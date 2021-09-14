@extends('layouts.adm.app', [
    'wsecond_title' => 'Pengaturan Website',
    'sidebar_menu' => 'website-configuration',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Pengaturan Website',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Pengaturan Website',
                'is_active' => true,
                'url' => false
            ],
        ]
    ]
])

@section('content')
<form class="card" method="POST" action="{{ route('adm.website-configuration.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="card-header">
        <h3 class="card-title">Pengaturan Website</h3>
    </div>
    <div class="card-body">
        @php
            $valtitle = old('title');
            if(!empty($title)){
                $valtitle = $title->value;
            }
            $description = old('description');
        @endphp

        <div class="form-group">
            <label>Judul Website</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="input-title" name="title" value="{{ $valtitle }}" placeholder="Judul dari Website">
        
            @error('title')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            <small class="text-muted">*Disarankan panjang karakter antara 50-65 karakter.</small>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>

            <textarea class="form-control @error('description') is-invalid @enderror" id="input-description" name="description" placeholder="Deskripsi dari Website">{!! $description !!}</textarea>
            @error('description')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="row">
            <div class="col-12 col-lg-6 form-group">
                <label>Favicon</label>
                <div class="preview-container">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('favicon') is-invalid @enderror" name="favicon" id="input-favicon" onchange="generateCustomPreview($(this))" accept=".jpg,.jpeg,.png">
                        <label class="custom-file-label" for="input-favicon">Choose file</label>
    
                        @error('favicon')
                        <div class='invalid-feedback'>{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Saran: Gunakan gambar dengan ekstensi PNG, Ukuran maksimal gambar adalah 500kb.</small>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-4 col-12">
                            <div class="img-preview mb-2">
                                <button type="button" class="btn btn-sm btn-danger d-block mb-2 mx-auto btn-preview_remove" onclick="removeCustomPreview($(this), '')" disabled>Reset Preview</button>
                                <img class="img-responsive" width="100%;" style="padding:.25rem;background:#eee;display:block;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 form-group">
                <label>Logo</label>
                <div class="preview-container">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('logo') is-invalid @enderror" name="logo" id="input-logo" onchange="generateCustomPreview($(this))" accept=".jpg,.jpeg,.png">
                        <label class="custom-file-label" for="input-logo">Choose file</label>
    
                        @error('logo')
                        <div class='invalid-feedback'>{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Saran: Gunakan gambar dengan ekstensi PNG, Ukuran maksimal gambar adalah 500kb.</small>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-4 col-12">
                            <div class="img-preview mb-2">
                                <button type="button" class="btn btn-sm btn-danger d-block mb-2 mx-auto btn-preview_remove" onclick="removeCustomPreview($(this), '')" disabled>Reset Preview</button>
                                <img class="img-responsive" width="100%;" style="padding:.25rem;background:#eee;display:block;">
                            </div>
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
            <button type="submit" id="btn-submit" class="btn btn-sm btn-primary">Submit</button>
        </div>
        <div class="clearfix"></div>
    </div>
    <!-- /.card-footer-->
</form>
@endsection

@section('js_plugins')
    {{-- Bootstrap Custom File Input --}}
    @include('layouts.adm.partials.plugins.bsfile-input-js')
@endsection

@section('js_inline')
<script>
    $(document).ready((e) => {
        bsCustomFileInput.init();
    });
</script>
@endsection