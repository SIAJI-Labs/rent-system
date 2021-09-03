@extends('layouts.adm.app', [
    'wsecond_title' => 'Kostumer: Tambah Baru',
    'sidebar_menu' => 'customer',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Kostumer: Tambah Baru',
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
                'title' => 'Tambah Baru',
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
<form class="card" method="POST" action="{{ route('adm.customer.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="card-header">
        <h3 class="card-title">Kustomer: Tambah Baru</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.customer.index') }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Daftar Kustomer">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Kustomer" name="name" id="input-name" value="{{ old('name') }}">
            @error('name')
            <span class="text-invalid">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea class="form-control @error('address') is-invalid @enderror" placeholder="Alamat Kostumer" name="address" id="input-address">{!! old('address') !!}</textarea>
            @error('address')
            <span class="text-invalid">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Kustomer" name="email" id="input-email" value="{{ old('email') }}">
            @error('email')
            <span class="text-invalid">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group col-12 col-lg-4 mb-lg-0">
                <label>Identitas</label>
                <input type="text" class="form-control @error('identity_type') is-invalid @enderror" placeholder="Jenis Identitas" name="identity_type" id="input-identity_type" value="{{ old('identity_type') }}">
                @error('identity_type')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                <small class="text-muted">Mohon diisi dengan jenis identitas (KTP, SIM, Passport, dll)</small>
            </div>
            <div class="form-group col-12 col-lg-8 mb-0">
                <label>Nomor Identitas</label>
                <input type="text" class="form-control @error('identity_number') is-invalid @enderror" placeholder="Nomor Identitas" name="identity_number" id="input-identity_number" value="{{ old('identity_number') }}">
                @error('identity_number')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Informasi Kontak (Max 5)</h3>
            </div>
            <div class="card-body">
                @php $contactStart = 1; @endphp

                <div id="contact-container">
                    <div class="contact-item" data-loop="0">
                        <div class="form-row">
                            <div class="form-group col-12 col-lg-4 mb-lg-0">
                                <label>Jenis</label>
                                <select class="form-control form-select2 form-contact @error('contact.0.type') is-invalid @enderror" name="contact[0][type]">
                                    <option value="mobile" {{ old('contact.0.type') == 'mobile' ? 'selected' : '' }}>No HP</option>
                                    <option value="phone" {{ old('contact.0.type') == 'phone' ? 'selected' : '' }}>No Telpon</option>
                                    <option value="instagram" {{ old('contact.0.type') == 'instagram' ? 'selected' : '' }}>Link Profile Instagram</option>
                                    <option value="twitter" {{ old('contact.0.type') == 'twitter' ? 'selected' : '' }}>Link Profile Twitter</option>
                                    <option value="facebook" {{ old('contact.0.type') == 'facebook' ? 'selected' : '' }}>Link Profile Facebook</option>
                                </select>

                                @error('contact.0.type')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-lg-8 mb-0">
                                <label>Informasi</label>
                                <div class="contact-information">
                                    @if(old('contact'))
                                        @if (old('contact.0.type') && (old('contact.0.type') != 'mobile' && old('contact.0.type') != 'phone'))
                                            <input type="text" class="form-control @error('contact.0.value') is-invalid @enderror" name="contact[0][value]" placeholder="Informasi Kontak" value="{{ old('contact.0.value') }}">
                                            <small class="text-muted">Mohon isi dengan url lengkap (Contoh: https://domain.com/profile)</small>
                                        @else
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">+62</span>
                                                </div>
                                                <input type="text" class="form-control @error('contact.0.value') is-invalid @enderror" name="contact[0][value]" placeholder="Informasi Kontak" value="{{ old('contact.0.value') }}">
                                            </div>
                                            <small class="text-muted">Mohon gunakan format +6281234567890</small>
                                        @endif
                                    @else
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">+62</span>
                                            </div>
                                            <input type="text" class="form-control @error('contact.0.value') is-invalid @enderror" name="contact[0][value]" placeholder="Informasi Kontak" value="{{ old('contact.0.value') }}">
                                        </div>
                                        <small class="text-muted">Mohon gunakan format +6281234567890</small>
                                    @endif
                                </div>
                            
                                @error('contact.0.value')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if (old('contact'))
                        @foreach (old('contact') as $key => $item)
                            @if (!$loop->first)
                                {{-- This is the first iteration --}}
                                <div class="contact-item" data-loop="{{ $key }}">
                                    <hr style="margin-bottom: .5rem"/>
                                    <div class="form-row">
                                        <div class="form-group col-12 col-lg-4">
                                            <label>Jenis</label>
                                            <select class="form-control form-select2 form-contact @error('contact.'.$key.'.type') is-invalid @enderror" name="contact[{{ $key }}][type]">
                                                <option value="mobile" {{ old('contact.'.$key.'.type') == 'mobile' ? 'selected' : '' }}>No HP</option>
                                                <option value="phone" {{ old('contact.'.$key.'.type') == 'phone' ? 'selected' : '' }}>No Telpon</option>
                                                <option value="instagram" {{ old('contact.'.$key.'.type') == 'instagram' ? 'selected' : '' }}>Link Profile Instagram</option>
                                                <option value="twitter" {{ old('contact.'.$key.'.type') == 'twitter' ? 'selected' : '' }}>Link Profile Twitter</option>
                                                <option value="facebook" {{ old('contact.'.$key.'.type') == 'facebook' ? 'selected' : '' }}>Link Profile Facebook</option>
                                            </select>
            
                                            @error('contact.'.$key.'.type')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-12 col-lg-8">
                                            <label>Informasi</label>

                                            <div class="contact-information">
                                                @if(old('contact'))
                                                    @if (old('contact.'.$key.'.type') && (old('contact.'.$key.'.type') != 'mobile' && old('contact.'.$key.'.type') != 'phone'))
                                                        <div class="input-group">
                                                            <input type="text" class="form-control @error('contact.'.$key.'.value') is-invalid @enderror" name="contact[{{ $key }}][value]" placeholder="Informasi Kontak" value="{{ old('contact.'.$key.'.value') }}">
                                                            
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-sm btn-danger contact-remove"><i class="fas fa-trash mr-2"></i>Hapus</button>
                                                            </div>
                                                        </div>    
                                                        
                                                        <small class="text-muted">Mohon isi dengan url lengkap (Contoh: https://domain.com/profile)</small>
                                                        @error('contact.'.$key.'.value')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                        @enderror
                                                    @else
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">+62</span>
                                                            </div>
                                                            <input type="text" class="form-control @error('contact.'.$key.'.value') is-invalid @enderror" name="contact[{{ $key }}][value]" placeholder="Informasi Kontak" value="{{ old('contact.'.$key.'.value') }}">
                                                            
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-sm btn-danger contact-remove"><i class="fas fa-trash mr-2"></i>Hapus</button>
                                                            </div>
                                                        </div>
                                                        
                                                        <small class="text-muted">Mohon gunakan format +6281234567890</small>
                                                        @error('contact.'.$key.'.value')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                        @enderror
                                                    @endif
                                                @else
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">+62</span>
                                                        </div>
                                                        <input type="text" class="form-control @error('contact.0.value') is-invalid @enderror" name="contact[0][value]" placeholder="Informasi Kontak" value="{{ old('contact.0.value') }}">
                                                    
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-sm btn-danger contact-remove"><i class="fas fa-trash mr-2"></i>Hapus</button>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">Mohon gunakan format +6281234567890</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @php $contactStart = $key; @endphp
                        @endforeach
                    @endif
                </div>

                <hr/>
                <button type="button" class="btn btn-sm btn-primary" id="btn_contact-add_more"><i class="fas fa-plus mr-2"></i>Tambah Lainnya</button>
            </div>
        </div>

        <div class="card mt-4 mb-0">
            <div class="card-header">
                <h3 class="card-title">Barang Jaminan (Max 3)</h3>
            </div>
            <div class="card-body">
                <div class="callout callout-info">
                    <h6>Catatan!</h6>
  
                    <small class="text-muted d-block">*Jenis Jaminan adalah Jenis barang yang digunakan sebagai jaminan (Misal: KTP, SIM, dll)</small>
                    <small class="text-muted d-block">*No Identitas yang digunakan untuk Jaminan (Misal: Jenis KTP, maka No Jaminan adalah NIK)</small>
                    <small class="text-muted d-block">*Ukuran maksimal gambar adalah 1000kb.</small>
                    @if($errors->any())
                        <div class='invalid-feedback d-block'>**Ada kesalahan ketika meng-upload data. Mohon upload ulang dokumen terkait</div>
                    @endif
                </div>

                @php $mortgageStart = 1; @endphp
                <div id="mortgage-container">
                    <div class="mortgage-item" data-loop="0">
                        <div class="form-row">
                            <div class="form-group col-6 col-lg-2 mb-lg-0">
                                <label>Jenis</label>
                                <input type="text" class="form-control @error('mortgage.0.type') is-invalid @enderror" placeholder="Jenis Jaminan" name="mortgage[0][type]" value="{{ old('mortgage.0.type') }}">
                                @error('mortgage.0.type')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-6 col-lg-4 mb-lg-0">
                                <label>No Jaminan</label>
                                <input type="text" class="form-control @error('mortgage.0.value') is-invalid @enderror" placeholder="No Jaminan" name="mortgage[0][value]" value="{{ old('mortgage.0.value') }}">
                                @error('mortgage.0.value')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-lg-6 mb-0">
                                <label>Foto</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('mortgage.0.file') is-invalid @enderror" name="mortgage[0][file]" accept=".jpg,.jpeg,.png">
                                    <label class="custom-file-label" for="input-pict">Choose file</label>
                
                                    @error('mortgage.0.file')
                                    <div class='invalid-feedback d-block'>{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (old('mortgage'))
                        @foreach (old('mortgage') as $key => $item)
                            @if (!$loop->first)
                                {{-- This is the first iteration --}}
                                <div class="mortgage-item" data-loop="{{ $key }}">
                                    <hr style="margin-bottom: .5rem"/>
                                    <div class="form-row">
                                        <div class="form-group col-6 col-lg-2 mb-lg-0">
                                            <label>Jenis</label>
                                            <input type="text" class="form-control @error('mortgage.'.$key.'.type') is-invalid @enderror" placeholder="Jenis Jaminan" name="mortgage[{{ $key }}][type]" value="{{ old('mortgage.'.$key.'.type') }}">
                                            @error('mortgage.'.$key.'.type')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-6 col-lg-4 mb-lg-0">
                                            <label>No Jaminan</label>
                                            <input type="text" class="form-control @error('mortgage.'.$key.'.value') is-invalid @enderror" placeholder="No Jaminan" name="mortgage[{{ $key }}][value]" value="{{ old('mortgage.'.$key.'.value') }}">
                                            @error('mortgage.0.value')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-12 col-lg-6 mb-0">
                                            <label>Foto</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input @error('mortgage.'.$key.'.file') is-invalid @enderror" name="mortgage[{{ $key }}][file]" accept=".jpg,.jpeg,.png">
                                                    <label class="custom-file-label" for="input-pict">Choose file</label>
                                                </div>

                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-sm btn-danger contact-remove"><i class="fas fa-trash mr-2"></i>Hapus</button>
                                                </div>
                                            </div>

                                            @error('mortgage.'.$key.'.file')
                                            <div class='invalid-feedback d-block'>{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @php $mortgageStart++; @endphp
                        @endforeach
                    @endif
                </div>

                <hr/>
                <button type="button" class="btn btn-sm btn-primary" id="btn_mortgage-add_more"><i class="fas fa-plus mr-2"></i>Tambah Lainnya</button>
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
    {{-- Bootstrap Custom File Input --}}
    @include('layouts.adm.partials.plugins.bsfile-input-js')
@endsection

@section('js_inline')
<script>
    const select2Init = () => {
        $(".form-select2").select2({
            theme: 'bootstrap4',
            allowClear: true,
        });
    }
    const contactFieldUpdate = () => {
        $(".form-contact").change((e) => {
            let el = $(e.target);
            let item = $(el).closest('.contact-item');
            let select_data = $(e.target).select2('data');
            let data_id = '';
            let data_text = '';

            if(!(jQuery.isEmptyObject(select_data))){
                data_id = select_data[0].id;
                data_text = select_data[0].text;
            }

            $(item).find('.contact-information').empty();
            if(data_id != 'mobile' && data_id != 'phone'){
                // Link
                if($(item).data('loop') != "0"){
                    $(`
                        <div class="input-group">
                            <input type="text" class="form-control" name="contact[${$(item).data('loop')}][value]" placeholder="Informasi Kontak">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-danger contact-remove"><i class="fas fa-trash mr-2"></i>Hapus</button>
                            </div>
                        </div>
                        <small class="text-muted">Mohon isi dengan url lengkap (Contoh: https://domain.com/profile)</small>
                    `).appendTo($(item).find('.contact-information'));
                } else {
                    // First Elem
                    $(`
                        <div class="input-group">
                            <input type="text" class="form-control" name="contact[${$(item).data('loop')}][value]" placeholder="Informasi Kontak">
                        </div>
                        <small class="text-muted">Mohon isi dengan url lengkap (Contoh: https://domain.com/profile)</small>
                    `).appendTo($(item).find('.contact-information'));
                }
            } else {
                // Phone
                if($(item).data('loop') != "0"){
                    $(`
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">+62</span>
                            </div>
                            <input type="text" class="form-control" name="contact[${$(item).data('loop')}][value]" placeholder="Informasi Kontak">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-danger contact-remove"><i class="fas fa-trash mr-2"></i>Hapus</button>
                            </div>
                        </div>
                        <small class="text-muted">Mohon gunakan format +6281234567890</small>
                    `).appendTo($(item).find('.contact-information'));
                } else {
                    // First Elem
                    $(`
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">+62</span>
                            </div>
                            <input type="text" class="form-control" name="contact[${$(item).data('loop')}][value]" placeholder="Informasi Kontak">
                        </div>
                        <small class="text-muted">Mohon gunakan format +6281234567890</small>
                    `).appendTo($(item).find('.contact-information'));
                }
            }
        });
    }

    let select2_query = {};
    $(document).ready((e) => {
        bsCustomFileInput.init();

        var contact_start = "{{ $contactStart }}";
        var mortgage_start = "{{ $mortgageStart }}";
        select2Init();
        contactFieldUpdate();

        // Add More Contact
        let contact_wrap = $("#contact-container");
        let contact_add = $("#btn_contact-add_more");
        $(contact_add).click((e) => {
            console.log(`Add More contact is running...`);
            contact_start++;

            $(`
                <div class="contact-item" style="display:none" data-loop="${contact_start}">
                    <hr style="margin-bottom: .5rem"/>
                    <div class="form-row">
                        <div class="form-group col-12 col-lg-4 mb-lg-0">
                            <label>Jenis</label>
                            <select class="form-control form-select2 form-contact" name="contact[${contact_start}][type]">
                                <option value="mobile">No HP</option>
                                <option value="phone">No Telpon</option>
                                <option value="instagram">Link Profile Instagram</option>
                                <option value="twitter">Link Profile Twitter</option>
                                <option value="facebook">Link Profile Facebook</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-8 mb-0">
                            <label>Informasi</label>
                            <div class="contact-information">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+62</span>
                                    </div>
                                    <input type="text" class="form-control" name="contact[${contact_start}][value]" placeholder="Informasi Kontak">
                                
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-sm btn-danger contact-remove"><i class="fas fa-trash mr-2"></i>Hapus</button>
                                    </div>
                                </div>
                                <small class="text-muted">Mohon gunakan format +6281234567890</small>
                            </div>
                        </div>
                    </div>
                </div>
            `).appendTo($(contact_wrap)).slideDown();
            setTimeout((e) => {
                select2Init();
                contactFieldUpdate();

                let item_count = $(".contact-item").length;
                console.log(`Item: ${item_count}`);
                if(item_count >= 5){
                    $("#btn_contact-add_more").attr('disabled', true);
                }
            });
        });
        $(contact_wrap).on('click', '.contact-remove', (e) => {
            const contact_item = $(e.target).closest('.contact-item');
            $(contact_item).slideUp((e) => {
                $(contact_item).remove();

                setTimeout((e) => {
                    let item_count = $(".contact-item").length;
                    console.log(`Item after delete: ${item_count}`);
                    if(item_count < 5){
                        $("#btn_contact-add_more").attr('disabled', false);
                    }
                });
            });
        });

        // Add More Mortgage
        let mortgage_wrap = $("#mortgage-container");
        let mortgage_add = $("#btn_mortgage-add_more");
        $(mortgage_add).click((e) => {
            console.log(`Add More mortgage is running...`);
            mortgage_start++;

            $(`
                <div class="mortgage-item" data-loop="${mortgage_start}" style="display:none">
                    <hr style="margin-bottom: .5rem"/>

                    <div class="form-row">
                        <div class="form-group col-6 col-lg-2 mb-lg-0">
                            <label>Jenis</label>
                            <input type="text" class="form-control" placeholder="Jenis Jaminan" name="mortgage[${mortgage_start}][type]">
                        </div>
                        <div class="form-group col-6 col-lg-4 mb-lg-0">
                            <label>No Jaminan</label>
                            <input type="text" class="form-control" placeholder="No Jaminan" name="mortgage[${mortgage_start}][value]">
                        </div>
                        <div class="form-group col-12 col-lg-6 mb-0">
                            <label>Foto</label>

                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="mortgage[${mortgage_start}][file]" accept=".jpg,.jpeg,.png">
                                    <label class="custom-file-label" for="input-pict">Choose file</label>
                                </div>
                                
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-sm btn-danger mortgage-remove"><i class="fas fa-trash mr-2"></i>Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `).appendTo($(mortgage_wrap)).slideDown();
            setTimeout((e) => {
                bsCustomFileInput.init();

                let item_count = $(".mortgage-item").length;
                console.log(`Item: ${item_count}`);
                if(item_count >= 3){
                    $("#btn_mortgage-add_more").attr('disabled', true);
                }
            });
        });
        $(mortgage_wrap).on('click', '.mortgage-remove', (e) => {
            const mortgage_item = $(e.target).closest('.mortgage-item');
            $(mortgage_item).slideUp((e) => {
                $(mortgage_item).remove();

                setTimeout((e) => {
                    let item_count = $(".mortgage-item").length;
                    console.log(`Item after delete: ${item_count}`);
                    if(item_count < 3){
                        $("#btn_mortgage-add_more").attr('disabled', false);
                    }
                });
            });
        });
    });
</script>
@endsection