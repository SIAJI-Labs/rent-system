@extends('layouts.adm.app', [
    'wsecond_title' => 'Dashboard',
    'sidebar_menu' => 'dashboard',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Dashboard',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => true,
                'url' => false
            ],
        ]
    ]
])

@section('content')
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box" id="box-transaction">
                <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>

                <div class="info-box-content pr-0">
                    <span class="info-box-text">Transaksi</span>
                    <select class="form-control" onchange="transactionStatistic()">
                        <option value="all">Semua Transaksi</option>
                        <option value="process">Transaksi sedang di Proses</option>
                        <option value="booking">Transaksi Booking</option>
                        <option value="complete">Transaksi Selesai</option>
                        <option value="cancel">Transaksi Dibatalkan</option>
                    </select>

                    <span class="info-box-number">-</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box" id="box-cashflow">
                <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>

                <div class="info-box-content pr-0">
                    <span class="info-box-text">Cashflow (Tahun: {{ date("Y") }})</span>
                    <select class="form-control" onchange="cashflowStatistic()">
                        <option value="all">Sepanjang Tahun</option>
                        @for ($i = 1; $i <= date("m"); $i++)
                            <option value="{{ $i }}">{{ formatBulan($i) }}</option>
                        @endfor
                    </select>
                    <span class="info-box-number">-</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box" id="box-customer">
                <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Kostumer</span>
                    <select class="form-control">
                        <option value="all">Semua Kostumer</option>
                        <option value="new">Kostumer Baru</option>
                        <option value="exist">Kostumer Loyal</option>
                    </select>

                    <span class="info-box-number">-</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box" id="box-visit">
                <span class="info-box-icon bg-danger"><i class="far fa-star"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Kunjungan (Tahun: {{ date("Y") }})</span>
                    <select class="form-control">
                        <option value="all">Sepanjang Tahun</option>
                    </select>
                    <span class="info-box-number">-</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>

    <div class="row">
        <div class="col-6 col-lg-4">
            <div class="card card-outline card-primary" id="list-booking">
                <div class="card-header">
                    <h3 class="card-title">Daftar Transaksi Booking</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <ul class="list-group" style="max-height: 325px; overflow: auto;">
                        <li class="list-group-item">Tidak ada data yang tersedia</li>
                    </ul>

                    <button type="button" class="btn btn-sm btn-primary mt-2 w-100 d-none" id="btn-booking_more" onclick="bookingList(bookingPage + 1)">Lihat lebih banyak</button>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="button" class="btn btn-sm btn-secondary w-100" onclick="bookingList(1)">
                        <i class="fas fa-sync-alt mr-1"></i> Muat Ulang</button>
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-6 col-lg-4">
            <div class="card card-outline card-warning" id="list-process">
                <div class="card-header">
                    <h3 class="card-title">Daftar Transaksi Proses</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <ul class="list-group" style="max-height: 325px; overflow: auto;">
                        <li class="list-group-item">Tidak ada data yang tersedia</li>
                    </ul>

                    <button type="button" class="btn btn-sm btn-primary mt-2 w-100 d-none" id="btn-booking_more" onclick="processList(processPage + 1)">Lihat lebih banyak</button>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="button" class="btn btn-sm btn-secondary w-100" onclick="processList(1)">
                        <i class="fas fa-sync-alt mr-1"></i> Muat Ulang</button>
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection

@section('js_inline')
<script>
    var bookingPage = 1;
    var processPage = 1;
    const transactionStatistic = () => {
        let el = $("#box-transaction");
        let filter_status = $(el).find('.form-control').val();

        $.get(`{{ route('adm.json.statistic.transaction') }}`, {'filter_status': filter_status}, (result) => {
            // console.log(result);
            $("#box-transaction").find('.info-box-number').text(formatRupiah(result.data, ''));
        });
    };
    const cashflowStatistic = () => {
        let el = $("#box-cashflow");
        let filter_month = $(el).find('.form-control').val();

        $.get(`{{ route('adm.json.statistic.cashflow') }}`, {'filter_month': filter_month}, (result) => {
            // console.log(result);
            let data = result.data;
            if(data.amount != null){
                $("#box-cashflow").find('.info-box-number').text(formatRupiah(data.amount));
            } else {
                $("#box-cashflow").find('.info-box-number').text('-');
            }
        });
    };
    const bookingList = (page) => {
        console.log(`Page`, page);
        $.get(`{{ route('adm.json.transaction.list') }}`, {'page': page, 'length': 5, 'status': 'booking'}, (result) => {
            console.log(result);
            let data = result.data;

            let el = $("#list-booking");
            let container = $(el).find('.list-group');
            let elementList = ``;

            // Empty List
            if(page == 1){
                $(container).empty();
            } else {
                bookingPage = page;
            }

            // Build List
            if(jQuery.isEmptyObject(data)){
                elementList += `<li class="list-group-item">Tidak ada data yang tersedia</li>`;
            } else {
                data.forEach((data, row) => {
                    let currDate = moment();
                    let startDate = moment(data.start_date);
                    let endDate = moment(data.end_date);
                    let badge = '';

                    if(moment(currDate).format('Do-MM-YYYY') == moment(startDate).format('Do-MM-YYYY') && moment(currDate).format("HH:mm:ss") < moment(startDate).format('HH:mm:ss')){
                        badge = `<span class="badge bg-primary"><small class="text-primary">XX</small></span>`;
                    } else if(currDate > startDate){
                        badge = `<span class="badge bg-secondary"><small class="text-secondary">XX</small></span>`;
                    }

                    elementList += `
                        <a href="{{ route('adm.transaction.index') }}/${data.uuid}" style="color: #000">
                            <li class="list-group-item">
                                <span>${badge} ${data.invoice}</span>
                                <hr class="my-1"/>
                                <div>
                                    <small>Tanggal Ambil/Sewa:</small>
                                    <small class="ml-1">${moment(data.start_date).format('Do MMMM YYYY, HH:mm')}</small><br/>
                                </div>
                                <div>
                                    <small>Kostumer:</small>
                                    <small class="ml-1">${data.customer.name}</small>
                                    <small class="mx-1">/</small>
                                    <small>Item:</small>
                                    <small class="ml-1">${Object.keys(data.transaction_item).length}</small>
                                </div>
                            </li>
                        </a>
                    `;
                });

                if(page < result.extra_data.last_page){
                    $("#btn-booking_more").addClass('d-block');
                    $("#btn-booking_more").removeClass('d-none');
                } else {
                    $("#btn-booking_more").removeClass('d-block');
                    $("#btn-booking_more").addClass('d-none');
                }
            }

            $(elementList).appendTo($(container));
        });
    }
    const processList = (page) => {
        console.log(`Page`, page);
        $.get(`{{ route('adm.json.transaction.list') }}`, {'page': page, 'length': 5, 'status': 'process'}, (result) => {
            console.log(result);
            let data = result.data;

            let el = $("#list-process");
            let container = $(el).find('.list-group');
            let elementList = ``;

            // Empty List
            if(page == 1){
                $(container).empty();
            } else {
                bookingPage = page;
            }

            // Build List
            if(jQuery.isEmptyObject(data)){
                elementList += `<li class="list-group-item">Tidak ada data yang tersedia</li>`;
            } else {
                data.forEach((data, row) => {
                    let currDate = moment();
                    let startDate = moment(data.start_date);
                    let endDate = moment(data.end_date);
                    let badge = '';

                    if(moment(currDate).format('Do-MM-YYYY') == moment(endDate).format('Do-MM-YYYY') && moment(currDate).format("HH:mm:ss") < moment(endDate).format('HH:mm:ss')){
                        badge = `<span class="badge bg-warning"><small class="text-warning">XX</small></span>`;
                    } else if(currDate > endDate){
                        badge = `<span class="badge bg-danger"><small class="text-danger">XX</small></span>`;
                    }
                    
                    elementList += `
                        <a href="{{ route('adm.transaction.index') }}/${data.uuid}" style="color: #000">
                            <li class="list-group-item">
                                <span>${badge} ${data.invoice}</span>
                                <hr class="my-1"/>
                                <div>
                                    <small>Tanggal Kembali:</small>
                                    <small class="ml-1">${moment(data.end_date).format('Do MMMM YYYY, HH:mm')}</small><br/>
                                </div>
                                <div>
                                    <small>Kostumer:</small>
                                    <small class="ml-1">${data.customer.name}</small>
                                    <small class="mx-1">/</small>
                                    <small>Item:</small>
                                    <small class="ml-1">${Object.keys(data.transaction_item).length}</small>
                                </div>
                            </li>
                        </a>
                    `;
                });

                if(page < result.extra_data.last_page){
                    $("#btn-booking_more").addClass('d-block');
                    $("#btn-booking_more").removeClass('d-none');
                } else {
                    $("#btn-booking_more").removeClass('d-block');
                    $("#btn-booking_more").addClass('d-none');
                }
            }

            $(elementList).appendTo($(container));
        });
    }

    $(document).ready((e) => {
        // Statistic
        transactionStatistic();
        cashflowStatistic();

        // List
        bookingList(bookingPage);
        processList(processPage);
    });
</script>
@endsection