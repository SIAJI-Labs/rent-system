@extends('layouts.adm.app', [
    'wbody_class' => 'sidebar-collapse',
    'wsecond_title' => 'Transaksi: Tambah Baru',
    'sidebar_menu' => 'transaction',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Transaksi: Tambah Baru',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Transaksi',
                'is_active' => false,
                'url' => route('adm.transaction.index')
            ], [
                'title' => 'Tambah Baru',
                'is_active' => true,
                'url' => false
            ],
        ]
    ]
])

@section('css_plugins')
    {{-- Select2 --}}
    @include('layouts.adm.partials.plugins.select2-css')
    {{-- Daterange Picker --}}
    @include('layouts.adm.partials.plugins.daterange-picker-css')
@endsection

@section('content')
<form class="card" method="POST" action="{{ route('adm.transaction.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="card-header">
        <h3 class="card-title">Transaksi: Tambah Baru</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.transaction.index') }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Daftar Transaksi">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($errors->any())
            {!! implode('', $errors->all('<div>:message</div>')) !!}
        @endif

        <div class="row">
            <div class="col-12 col-lg-6">
                <h6 class="title">Data Transaksi</h6>
                <hr/>

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
                    <label>Kasir</label>
                    <input type="text" class="form-control" value="{{ \Auth::user()->name }}" readonly>
                </div>

                <div class="form-group">
                    <label>Kostumer</label>
                    <input type="hidden" name="old_customer_id" value="{{ old('old_customer_id') }}" id="input-old_customer_id" readonly>
                    <input type="hidden" name="old_customer_id_text" value="{{ old('old_customer_id_text') }}" id="input-old_customer_id_text" readonly>

                    <select class="form-control @error('customer_id') is-invalid @enderror" id="input-customer_id" name="customer_id" style="width: 100% !important;">
                        @if(old('old_customer_id'))
                        <option value="{{ old('old_customer_id') }}" selected>{{ old('old_customer_id_text') }}</option>
                        @endif
                    </select>
                    @error('customer_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Jenis Transaksi</label>
                    <select class="form-control form-select2 @error('type') is-invalid @enderror" id="input-type" name="type" style="width: 100% !important;">
                        <option value="normal" {{ old('type') == 'normal' ? 'selected' : '' }}>Sewa / Normal</option>
                        <option value="booking" {{ old('type') == 'booking' ? 'selected' : '' }}>Booking</option>
                    </select>
                    @error('type')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Periode Sewa</label>
                    <input type="text" name="daterange" class="form-control @error('daterange') is-invalid @enderror" id="input-daterange" placeholder="Periode Sewa" autocomplete="off" value="{{ old('daterange') }}" required>
                    
                    @error('daterange')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea class="ckeditor d-none @error('note') is-invalid @enderror" name="note" id="input-note" placeholder="Catatan mengenai Transaksi akan berada disini...">{!! old('note') !!}</textarea>
                    @error('note')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <h6 class="title">Data Item</h6>
                <hr/>

                <div class="card">
                    <div class="card-body">
                        @php $productStart = 1; @endphp
                        <div id="product-container">
                            <div class="product-item" data-loop="0">
                                <div class="form-row">
                                    <div class="form-group col-12 col-lg-6">
                                        <label>Produk</label>
                                        <input type="hidden" name="product[0][old_product_id]" value="{{ old('product.0.old_product_id') }}" id="input_0-old_product_id" readonly>
                                        <input type="hidden" name="product[0][old_product_id_text]" value="{{ old('product.0.old_product_id_text') }}" id="input_0-old_product_id_text" readonly>
        
                                        <select class="form-control form-product @error('product.0.product_id') is-invalid @enderror" id="input_0-product_id" name="product[0][product_id]" style="width: 100% !important;" disabled>
                                            @if(old('product.0.old_product_id'))
                                            <option value="{{ old('product.0.old_product_id') }}" selected>{{ old('product.0.old_product_id_text') }}</option>
                                            @endif
                                        </select>
                                        @error('product.0.product_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="text-muted d-block store-needed">*Harap pilih toko terlebih dahulu</small>
                                    </div>
                                    <div class="form-group col-12 col-lg-6">
                                        <label>SN</label>
                                        <input type="hidden" name="product[0][old_sn_id]" value="{{ old('product.0.old_sn_id') }}" id="input_0-old_sn_id" readonly>
                                        <input type="hidden" name="product[0][old_sn_id_text]" value="{{ old('product.0.old_sn_id_text') }}" id="input_0-old_sn_id_text" readonly>
        
                                        <select class="form-control form-product-sn @error('product.0.sn_id') is-invalid @enderror" id="input_0-sn_id" name="product[0][sn_id]" style="width: 100% !important;" {{ old('product.0.old_product_id') && old('store_id') ? '' : 'disabled' }} disabled>
                                            @if(old('product.0.sn_id'))
                                            <option value="{{ old('product.0.old_sn_id') }}" selected>{{ old('product.0.old_sn_id_text') }}</option>
                                            @endif
                                        </select>
                                        @error('product.0.sn_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror

                                        <small class="text-muted d-block product-needed">*Harap pilih produk terlebih dahulu</small>
                                    </div>
                                </div>
    
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label>Biaya Sewa (@)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'digits': '0', 'allowMinus': 'false'" name="product[0][price]" id="input_0-price" class="form-control product-price @error('product.0.price') is-invalid @enderror" value="{{ old('product.0.price') ?? 0 }}" min="0" placeholder="Biaya Sewa" min="0" onchange="updatePrice()" required>
                                        </div>
                                        @error('product.0.price')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Diskon</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'digits': '0', 'allowMinus': 'false'" name="product[0][discount]" id="input_0-discount" class="form-control product-discount @error('product.0.discount') is-invalid @enderror" value="{{ old('product.0.discount') ?? 0 }}" min="0" placeholder="Diskon Sewa" min="0" onchange="updateDiscount()" required>
                                        </div>
                                        @error('product.0.discount')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
    
                                <div class="form-group">
                                    <label>Catatan</label>
                                    <textarea class="form-control @error('product.0.note') is-invalid @enderror" placeholder="Catatan Produk" name="product[0][note]">{!! old('product.0.note') !!}</textarea>
                                    @error('product.0.note')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            @if (old('product'))
                                @foreach (old('product') as $key => $item)
                                    @if (!($loop->first))
                                        <div class="product-item" data-loop="{{ $key }}">
                                            <div class="form-row">
                                                <div class="form-group col-12 col-lg-6">
                                                    <label>Produk</label>
                                                    <input type="hidden" name="product[{{ $key }}][old_product_id]" value="{{ old('product.'.$key.'.old_product_id') }}" id="input_{{ $key }}-old_product_id" readonly>
                                                    <input type="hidden" name="product[{{ $key }}][old_product_id_text]" value="{{ old('product.'.$key.'.old_product_id_text') }}" id="input_{{ $key }}-old_product_id_text" readonly>
                    
                                                    <select class="form-control form-product @error('product.'.$key.'.product_id') is-invalid @enderror" id="input_{{ $key }}-product_id" name="product[{{ $key }}][product_id]" style="width: 100% !important;">
                                                        @if(old('product.'.$key.'.old_product_id'))
                                                        <option value="{{ old('product.'.$key.'.old_product_id') }}" selected>{{ old('product.'.$key.'.old_product_id_text') }}</option>
                                                        @endif
                                                    </select>
                                                    @error('product.'.$key.'.product_id')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                    <small class="text-muted d-block store-needed">*Harap pilih toko terlebih dahulu</small>
                                                </div>
                                                <div class="form-group col-12 col-lg-6">
                                                    <label>SN</label>
                                                    <input type="hidden" name="product[{{ $key }}][old_sn_id]" value="{{ old('product.'.$key.'.old_sn_id') }}" id="input_{{ $key }}-old_sn_id" readonly>
                                                    <input type="hidden" name="product[{{ $key }}][old_sn_id_text]" value="{{ old('product.'.$key.'.old_sn_id_text') }}" id="input_{{ $key }}-old_sn_id_text" readonly>
                    
                                                    <select class="form-control form-product-sn @error('product.'.$key.'.sn_id') is-invalid @enderror" id="input_{{ $key }}-sn_id" name="product[{{ $key }}][sn_id]" style="width: 100% !important;" {{ old('product.'.$key.'.old_product_id') && old('store_id') ? '' : 'disabled' }}>
                                                        @if(old('product.'.$key.'.sn_id'))
                                                        <option value="{{ old('product.'.$key.'.old_sn_id') }}" selected>{{ old('product.'.$key.'.old_sn_id_text') }}</option>
                                                        @endif
                                                    </select>
                                                    @error('product.'.$key.'.sn_id')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror

                                                    <small class="text-muted d-block product-needed">*Harap pilih produk terlebih dahulu</small>
                                                </div>
                                            </div>
                
                                            <div class="form-row">
                                                <div class="form-group col-6">
                                                    <label>Biaya Sewa (@)</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'digits': '0', 'allowMinus': 'false'" name="product[{{ $key }}][price]" id="input_{{ $key }}-price" class="form-control product-price @error('product.'.$key.'.price') is-invalid @enderror" value="{{ old('product.'.$key.'.price') ?? 0 }}" min="0" placeholder="Biaya Sewa" min="0" onchange="updatePrice()" required>
                                                    </div>
                                                    @error('product.'.$key.'.price')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label>Diskon</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'digits': '0', 'allowMinus': 'false'" name="product[{{ $key }}][discount]" id="input_{{ $key }}-discount" class="form-control product-discount @error('product.'.$key.'.discount') is-invalid @enderror" value="{{ old('product.'.$key.'.discount') ?? 0 }}" min="0" placeholder="Diskon Sewa" min="0" onchange="updateDiscount()" required>
                                                    </div>
                                                    @error('product.'.$key.'.discount')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                
                                            <div class="form-group">
                                                <label>Catatan</label>

                                                <div class="input-group">
                                                    <textarea class="form-control @error('product.'.$key.'.note') is-invalid @enderror" placeholder="Catatan Produk" name="product[{{ $key }}][note]">{!! old('product.'.$key.'.note') !!}</textarea>
    
                                                    @if(!($loop->first))
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-sm btn-danger product-remove"><i class="fas fa-trash mr-2"></i>Hapus</button>
                                                        </div>
                                                    @endif
                                                </div>
                                                @error('product.'.$key.'.note')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <hr/>
                        <button type="button" class="btn btn-sm btn-primary" id="btn_product-add_more" {{ old('store_id') ? '' : 'disabled' }}><i class="fas fa-plus mr-2"></i>Tambah lainnya</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-end">
            <div class="col-12 col-lg-6">
                <div class="form-group row align-items-center">
                    <label class="col-sm-4 col-form-label">Jumlah Biaya</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'digits': '0', 'allowMinus': 'false'" name="sum_price" id="input-sum_price" class="form-control" value="0" min="0" placeholder="Jumlah Biaya Sewa" min="0" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label class="col-sm-4 col-form-label">Jumlah Diskon</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'digits': '0', 'allowMinus': 'false'" name="sum_discount" id="input-sum_discount" class="form-control" value="0" min="0" placeholder="Jumlah Diskon" min="0" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-sm-4 col-form-label">Lama Sewa</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'digits': '0', 'allowMinus': 'false'" name="periode" id="input-periode" class="form-control" value="0" min="0" placeholder="Lama Sewa" min="0" onchange="calculateSumAmount()" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">hari</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-sm-4 col-form-label">Biaya Tambahan</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'digits': '0', 'allowMinus': 'false'" name="extra_amount" id="input-extra_amount" class="form-control" value="0" min="0" placeholder="Biaya Tambahan" min="0" onchange="calculateSumAmount()">
                        </div>
                    </div>
                </div>

                <hr/>
                <div class="form-group row align-items-center">
                    <label class="col-sm-4 col-form-label">Total Biaya</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'digits': '0', 'allowMinus': 'false'" name="sum_amount" id="input-sum_amount" class="form-control" value="0" min="0" placeholder="Jumlah Biaya" min="0" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-sm-4 col-form-label">
                        <span>Bayar</span>

                        <div class="btn btn-group">
                            <button type="button" class="btn btn-xs btn-primary" onclick="setPaid(100)">Lunas</button>
                            <button type="button" class="btn btn-xs btn-success" onclick="setPaid(50)">50%</button>
                            <button type="button" class="btn btn-xs btn-warning" onclick="setPaid(25)">25%</button>
                        </div>
                    </label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'digits': '0', 'allowMinus': 'false'" name="paid" id="input-paid" class="form-control" value="0" min="0" placeholder="Biaya dibayarkan" min="0" onchange="calculateLeftOver()">
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row align-items-center">
                    <label class="col-sm-4 col-form-label">
                        <span>Kekurangan</span>
                    </label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'digits': '0', 'allowMinus': 'false'" name="leftover" id="input-leftover" class="form-control" value="0" min="0" placeholder="Kekurangan Biaya" min="0" readonly>
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
    {{-- Select2 --}}
    @include('layouts.adm.partials.plugins.select2-js')
    {{-- Bootstrap Custom File Input --}}
    @include('layouts.adm.partials.plugins.bsfile-input-js')
    {{-- Daterange Picker --}}
    @include('layouts.adm.partials.plugins.daterange-picker-js')
    {{-- Input Mask --}}
    @include('layouts.adm.partials.plugins.input-mask-js')
@endsection

@section('js_inline')
<script>
    // Init on Product Selection
    const productSelect = () => {
        if($("#input-store_id").val()){
            $(".form-product").attr('disabled', false);
            $(".store-needed").removeClass('d-block').addClass('d-none');
        }
        
        $(".form-product").select2({
            placeholder: 'Cari Produk',
            theme: 'bootstrap4',
            allowClear: true,
            ajax: {
                url: "{{ route('adm.json.select2.product.all') }}",
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1,
                        store_id: $("#input-store_id").val()
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
        $(".form-product").change((e) => {
            let el = $(e.target).closest('.product-item');
            let product_sn = $(el).find('.form-product-sn');
            let select_data = $(e.target).select2('data');

            let data_id = '';
            let data_text = '';
            let price = '0';
            let discount = '0';

            if(!(jQuery.isEmptyObject(select_data))){
                data_id = select_data[0].id;
                data_text = select_data[0].text;
                price = select_data[0].price;
                discount = '0';

                $(product_sn).attr('disabled', false);
                $(el).find('.product-needed').removeClass('d-block').addClass('d-none');
            } else {
                $(product_sn).attr('disabled', true);
                $(el).find('.product-needed').removeClass('d-none').addClass('d-block');
            }
            $(product_sn).val('').change();
            $(`#input_${$(el).attr('data-loop')}-old_product_id`).val(data_id);
            $(`#input_${$(el).attr('data-loop')}-old_product_id_text`).val(data_text);

            $(el).find('.product-price').val(price ?? 0).change();
            $(el).find('.product-discount').val(discount ?? 0).change();

            updatePrice();
            updateDiscount();
        });
    }
    // Init on Product SN Selection
    const productSnSelect = (loop) => {
        $(".form-product-sn").select2({
            placeholder: 'Cari Produk',
            theme: 'bootstrap4',
            allowClear: true,
            ajax: {
                url: "{{ route('adm.json.select2.product.serial-number.all') }}",
                delay: 250,
                data: function (params) {
                    let el = $(this).closest('.product-item');
                    var query = {
                        search: params.term,
                        page: params.page || 1,
                        product_id: $(el).find('.form-product').val(),
                        store_id: $("#input-store_id").val(),
                        daterange: $("#input-daterange").val()
                    }

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function (data, params) {
                    var items = $.map(data.data, function(obj){
                        obj.id = obj.id;
                        obj.text = obj.serial_number;

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
        $(".form-product-sn").change((e) => {
            let el = $(e.target).closest('.product-item');
            let select_data = $(e.target).select2('data');

            let data_id = '';
            let data_text = '';
            let price = '0';
            let discount = '0';

            if(!(jQuery.isEmptyObject(select_data))){
                data_id = select_data[0].id;
                data_text = select_data[0].text;
            }
            $(`#input_${$(el).attr('data-loop')}-old_sn_id`).val(data_id);
            $(`#input_${$(el).attr('data-loop')}-old_sn_id_text`).val(data_text);
        });
    }
    // Total Price Calculation
    const updatePrice = () => {
        // Update Price
        let sum_price = 0;
        $(".product-price").each((row, el) => {
            let val = $(el).inputmask('unmaskedvalue');

            sum_price += parseInt(val);
        });
        $("#input-sum_price").val(sum_price);

        calculateSumAmount();
    }
    // Total Discount Calculation
    const updateDiscount = () => {
        let sum_discount = 0;
        $(".product-discount").each((row, el) => {
            let val = $(el).inputmask('unmaskedvalue');
            sum_discount += parseInt(val);
        });
        $("#input-sum_discount").val(sum_discount);

        calculateSumAmount();
    }
    // Calculate Sum Amount
    const calculateSumAmount = () => {
        let calc = 0;
        let sum = $("#input-sum_price").inputmask('unmaskedvalue');
        let discount = $("#input-sum_discount").inputmask('unmaskedvalue');
        let extra_charge = $("#input-extra_amount").inputmask('unmaskedvalue');
        let days = $("#input-periode").inputmask('unmaskedvalue');

        calc = ((parseInt(sum) - parseInt(discount)) * parseInt(days)) + parseInt(extra_charge);
        $("#input-sum_amount").val(calc ?? 0);
        calculateLeftOver();
    }
    // Calculate Paid Amount
    const setPaid = (percentage) => {
        console.log("Set Paid is running...");

        let sumAmount = $("#input-sum_amount").inputmask('unmaskedvalue');
        let calculate = (parseInt(percentage) / 100) * parseInt(sumAmount);

        $("#input-paid").val(calculate ?? 0);
        calculateLeftOver();
    }
    // Calculate Leftover
    const calculateLeftOver = () => {
        let calculate = 0;
        let paid = $("#input-paid").inputmask('unmaskedvalue');
        let sumAmount = $("#input-sum_amount").inputmask('unmaskedvalue');

        calculate = parseInt(sumAmount) - parseInt(paid);
        $("#input-leftover").val(calculate ?? 0);
    }

    $(document).ready((e) => {
        $(":input").inputmask(undefined);

        let select2_query = {};
        $(".form-select2").select2({
            theme: 'bootstrap4',
            allowClear: true,
        });
        $("#input-store_id").select2({
            placeholder: 'Cari Toko',
            theme: 'bootstrap4',
            allowClear: false,
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
        $("#input-customer_id").select2({
            placeholder: 'Cari Kostumer',
            theme: 'bootstrap4',
            allowClear: true,
            ajax: {
                url: "{{ route('adm.json.select2.customer.all') }}",
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
        productSelect();
        productSnSelect(0);
        updatePrice();
        updateDiscount();

        $('input[name="daterange"]').daterangepicker({
            startDate: "{{ !empty(old('daterange')) ? date("m/d/Y H:i", strtotime(explode("-", old('daterange'))[0])) : date("m/d/Y 00:00") }}",
            endDate: "{{ !empty(old('daterange')) ? date("m/d/Y H:i", strtotime(explode("-", old('daterange'))[1])) : date("m/d/Y 00:00", strtotime("+1 day")) }}",
            timePicker: true,
            timePicker24Hour: true,
            opens: 'left',
            locale: {
                format: 'MM/DD/YYYY HH:mm'
            }
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });

        var product_start = "{{ $productStart }}";
        // Add More Mortgage
        let product_wrap = $("#product-container");
        let product_add = $("#btn_product-add_more");
        $(product_add).click((e) => {
            console.log(`Add More product is running...`);
            product_start++;

            $(`
                <div class="product-item" data-loop="${product_start}" style="display:none">
                    <hr style="margin-bottom: .5rem"/>

                    <div class="form-row">
                        <div class="form-group col-12 col-lg-6">
                            <label>Produk</label>
                            <input type="hidden" name="product[${product_start}][old_product_id]" id="input_${product_start}-old_product_id" readonly>
                            <input type="hidden" name="product[${product_start}][old_product_id_text]" id="input_${product_start}-old_product_id_text" readonly>

                            <select class="form-control form-product" id="input_${product_start}-product_id" name="product[${product_start}][product_id]" style="width: 100% !important;" disabled>
                            </select>
                            <small class="text-muted d-block store-needed">*Harap pilih toko terlebih dahulu</small>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label>SN</label>
                            <input type="hidden" name="product[${product_start}][old_sn_id]" id="input_${product_start}-old_sn_id" readonly>
                            <input type="hidden" name="product[${product_start}][old_sn_id_text]" id="input_${product_start}-old_sn_id_text" readonly>

                            <select class="form-control form-product-sn" id="input_${product_start}-sn_id" name="product[${product_start}][sn_id]" style="width: 100% !important;" disabled>
                            </select>
                            <small class="text-muted d-block product-needed">*Harap pilih produk terlebih dahulu</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-6">
                            <label>Biaya Sewa (@)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'digits': '0', 'allowMinus': 'false'" name="product[${product_start}][price]" id="input_${product_start}-price" class="form-control product-price" min="0" placeholder="Biaya Sewa" min="0" value="0" onchange="updatePrice()" required>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label>Diskon</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'digits': '0', 'allowMinus': 'false'" name="product[${product_start}][discount]" id="input_${product_start}-discount" class="form-control product-discount" min="0" placeholder="Diskon Sewa" min="0" value="0" onchange="updateDiscount()" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Catatan</label>

                        <div class="input-group">
                            <textarea class="form-control" placeholder="Catatan Produk" name="product[${product_start}][note]"></textarea>
                            
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-danger product-remove"><i class="fas fa-trash mr-2"></i>Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            `).appendTo($(product_wrap)).slideDown();
            setTimeout((e) => {
                // $(":input").inputmask(undefined);
                $(`.product-item[data-loop="${product_start}"]`).find($(":input")).inputmask(undefined);

                productSelect();
                productSnSelect(product_start);

                let item_count = $(".product-item").length;
                console.log(`Item: ${item_count}`);
            });
        });
        $(product_wrap).on('click', '.product-remove', (e) => {
            const product_item = $(e.target).closest('.product-item');
            $(product_item).slideUp((e) => {
                $(product_item).remove();
            });
        });
    });

    $("#input-customer_id").change((e) => {
        let select_data = $(e.target).select2('data');
        let data_id = '';
        let data_text = '';

        if(!(jQuery.isEmptyObject(select_data))){
            data_id = select_data[0].id;
            data_text = select_data[0].text;
        } 

        $("#input-old_customer_id").val(data_id);
        $("#input-old_customer_id_text").val(data_text);
    });
    $("#input-store_id").change((e) => {
        let select_data = $(e.target).select2('data');
        let data_id = '';
        let data_text = '';

        if(!(jQuery.isEmptyObject(select_data))){
            data_id = select_data[0].id;
            data_text = select_data[0].text;

            $(".store-needed").removeClass('d-block').addClass('d-none');
            $(".form-product").attr('disabled', false);
            $(".form-product").val('').change();
            $("#btn_product-add_more").attr('disabled', false);
        } else {
            $(".store-needed").removeClass('d-none').addClass('d-block');
            $(".form-product").attr('disabled', true);
            $(".form-product").val('').change();
            $("#btn_product-add_more").attr('disabled', true);
        }

        $("#input-old_store_id").val(data_id);
        $("#input-old_store_id_text").val(data_text);
    });
    $("#input-customer_id").change((e) => {
        let select_data = $(e.target).select2('data');
        let data_id = '';
        let data_text = '';

        if(!(jQuery.isEmptyObject(select_data))){
            data_id = select_data[0].id;
            data_text = select_data[0].text;
        } 

        $("#input-old_customer_id").val(data_id);
        $("#input-old_customer_id_text").val(data_text);
    });
    $("#input-daterange").change((e) => {
        let el = $(e.target);
        if($("#input-store_id").val()){
            $(".form-product-sn").val('').change();
        }

        setTimeout((e) => {
            let daterange = $(el).data('daterangepicker');
            let startDate = daterange.startDate.format('YYYY-MM-DD');
            let endDate = daterange.endDate.format('YYYY-MM-DD');

            let diff = moment(endDate).diff(moment(startDate), 'days');
            $("#input-periode").val(diff).change();
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