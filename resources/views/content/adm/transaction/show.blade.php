@extends('layouts.adm.app', [
    'wsecond_title' => 'Transaksi: Detail Data',
    'sidebar_menu' => 'transaction',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Transaksi: Detail Data',
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
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Transaksi / #{{ $data->invoice }}</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.transaction.index') }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Daftar Transaksi">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>
            <a href="{{ route('adm.transaction.invoice', $data->uuid) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Lihat Invoice Transaksi" target="_blank">
                <i class="fas fa-eye mr-1"></i> Invoice
            </a>
            <a href="{{ route('adm.transaction.edit', $data->uuid) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit Data Transaksi">
                <i class="far fa-edit mr-1"></i> Edit
            </a>
        </div>
    </div>
    <div class="card-body">
        @php
            $currDate = date("Y-m-d");
            $startDate = $data->start_date;
            $endDate = $data->end_date;
        @endphp
        @if ($data->status == 'process')
            @if ($currDate == date("Y-m-d", strtotime($endDate)) && (date("H:i:s") < date("H:i:s", strtotime($endDate))))
                <div class="alert alert-warning" role="alert">
                    Transaksi mendekati batas periode sewa yang telah ditentukan!
                </div>
            @elseif($currDate == date("Y-m-d", strtotime($endDate)) && date("H:i:s") > date("H:i:s", strtotime($endDate)))
                <div class="alert alert-danger" role="alert">
                    Transaksi melebihi periode sewa yang telah ditentukan!
                </div>
            @endif
        @elseif($data->status == 'booking')
            @if ($currDate == date("Y-m-d", strtotime($startDate)) && (date("H:i:s") < date("H:i:s", strtotime($endDate))))
                <div class="alert alert-primary" role="alert">
                    Transaksi mendekati awal periode sewa!
                </div>
            @elseif(($currDate == date("Y-m-d", strtotime($startDate)) || $currDate >= date("Y-m-d", strtotime($startDate))) && date("H:i:s") > date("H:i:s", strtotime($startDate)))
                <div class="alert alert-secondary" role="alert">
                    Transaksi belum diproses dan sudah melebihi awal periode sewa!
                </div>
            @endif
        @endif

        <table class="table table-bordered table-hover">
            <tr>
                <th>Invoice</th>
                <td>{{ $data->invoice }}</td>
            </tr>
            <tr>
                <th>Toko / Kasir</th>
                <td>
                    <a href="{{ route('adm.store.show', $data->store->uuid) }}">{{ $data->store->name }}</a> / <a href="javascript:void(0)">{{ $data->user->name }}</a>
                </td>
            </tr>
            <tr>
                <th>Kostumer</th>
                <td>
                    <a href="{{ route('adm.customer.show', $data->customer->uuid) }}">{{ $data->customer->name }}</a>
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @php
                        $colorBg = 'primary';
                        $status = 'Booking';
                        switch($data->status){
                            case 'booking':
                                $colorBg = 'warning';
                                $status = 'Booking';
                                break;
                            case 'complete':
                                $colorBg = 'success';
                                $status = 'Selesai';
                                break;
                            case 'cancel':
                                $colorBg = 'danger';
                                $status = 'Dibatalkan';
                                break;
                        }    
                    @endphp
                    <span class="badge badge-{{ $colorBg }}">{{ ucwords($status) }}</span>
                </td>
            </tr>
            <tr>
                <th>Periode Sewa</th>
                <td>({{ round((strtotime($data->end_date) - strtotime($data->start_date)) / (60 * 60 * 24)) }} hari) {{ date("d F Y, H:s", strtotime($data->start_date)).' - '.date("d F Y, H:s", strtotime($data->end_date)) }}</td>
            </tr>
            <tr>
                <th colspan="2">Catatan</th>
            </tr>
            <tr>
                <td colspan="2">{!! $data->note ?? '-' !!}</td>
            </tr>
            <tr>
                <th>Biaya Sewa</th>
                <td>{{ formatRupiah($data->amount) }}</td>
            </tr>
            <tr>
                <th>Potongan Biaya</th>
                <td>{{ formatRupiah($data->discount) }} (Jumlah: {{ formatRupiah($data->amount - $data->discount) }})</td>
            </tr>
            <tr>
                <th>Biaya Tambahan</th>
                <td>{{ formatRupiah($data->extra) }} (Jumlah: <b><u>{{ formatRupiah(($data->amount - $data->discount) + $data->extra) }}</u></b>)</td>
            </tr>
            <tr>
                <th>Terbayarkan</th>
                <td>{{ formatRupiah($data->paid) }} (<b><u>{!! (($data->amount - $data->discount) + $data->extra) == $data->paid ? 'Lunas' : ($data->status == 'cancel' ? '<span class="badge badge-danger">Dibatalkan</span>' : ('Kurang: '.formatRupiah((($data->amount - $data->discount) + $data->extra) - $data->paid))) !!}</u></b>)</td>
            </tr>
        </table>

        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Item Transaksi</h3>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped table-bordered" id="transaction_item-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Serial Number</th>
                            <th>Biaya @</th>
                            <th>Potongan @</th>
                            <th>Extra</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Keuangan Transaksi</h3>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped table-bordered" id="transaction_accounting-table">
                    <thead>
                        <tr>
                            <th>Kasir</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="card mt-4 mb-0">
            <div class="card-header">
                <h3 class="card-title">Update Transaksi</h3>
            </div>
            <div class="card-body" style="max-height:250px;overflow:auto">
                <div class="timeline" id="logs-timeline">
                    @php
                        $date = null;   
                    @endphp
                    @foreach($data->transactionLog()->orderBy('created_at', 'desc')->get() as $logs)
                        @php
                            $audit = \OwenIt\Auditing\Models\Audit::whereNotNull('extra_type')
                                ->where('extra_type', get_class($logs))
                                ->where('extra_id', $logs->id)
                                ->get()
                        @endphp

                        @if(date("M d, Y", strtotime($date)) != date("M d, Y", strtotime($logs->created_at)))
                    <div class="time-label" id="label-{{ date('dmy', strtotime($logs->created_at)) }}">
                        <span class="bg-red">{{ date("M d, Y", strtotime($logs->created_at)) }}</span>
                    </div>
        
                    <div>
                        <i class="fas fa-envelope bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ date("H:i:s", strtotime($logs->created_at)) }}</span>
                            <h3 class="timeline-header"><a href="#">{{ $logs->user_id ? $logs->user->name : 'System' }}</a></h3>
                            <div class="timeline-body">
                                <span>{!! $logs->log !!}</span>
                                
                                @if (!empty($audit) && count($audit) > 0)
                                    {{-- Transaction Audit --}}
                                    @include('content.adm.transaction.partials.audit', $audit)
                                @endif
                            </div>
                        </div>
                    </div>
                        @else
                    <div>
                        <i class="fas fa-envelope bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ date("H:i:s", strtotime($logs->created_at)) }}</span>
                            <h3 class="timeline-header"><a href="#">{{ $logs->user_id ? $logs->user->name : 'System' }}</a></h3>
                            <div class="timeline-body">
                                <span>{!! $logs->log !!}</span>
                                
                                @if (!empty($audit) && count($audit) > 0)
                                    {{-- Transaction Audit --}}
                                    @include('content.adm.transaction.partials.audit', $audit)
                                @endif
                            </div>
                        </div>
                    </div>
                        @endif
        
                        @php
                            $date = $logs->created_at;
                        @endphp
                    @endforeach
                    <!-- END timeline item -->
                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content_modal')
<div class="modal fade" id="modal-transactionItem">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-striped table-bordered">
                    <tr>
                        <th>Produk</th>
                        <td class="item-product"></td>
                    </tr>
                    <tr>
                        <th>Produk SN</th>
                        <td class="item-product_sn"></td>
                    </tr>
                    <tr>
                        <th>Biaya @</th>
                        <td class="item-price"></td>
                    </tr>
                    <tr>
                        <th>Diskon</th>
                        <td class="item-discount"></td>
                    </tr>
                    <tr>
                        <th>Jumlah Biaya @</th>
                        <td class="item-sum_price"></td>
                    </tr>
                    <tr>
                        <th colspan="2">Catatan</th>
                    </tr>
                    <tr>
                        <td class="item-note" colspan="2"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection

@section('js_plugins')
    {{-- Datatable --}}
    @include('layouts.adm.partials.plugins.datatable-js')
@endsection

@section('js_inline')
<script>
    $.fn.dataTable.moment( 'dddd, MMMM Do, YYYY' );
    
    $(document).ready((e) => {
        $("#transaction_item-table").DataTable({
            order: [0, 'asc'],
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('adm.json.datatable.transaction.item.all', $data->uuid) }}",
                type: "GET",
            },
            success: (result) => {
                console.log(result);
            },
            columns: [
                { "data": "product.name", "name": "product.name" },
                { "data": "product_detail.serial_number", "name": "product_detail.serial_number" },
                { "data": "price" },
                { "data": "discount" },
                { "data": null },
            ],
            columnDefs: [
                {
                    "targets": "_all",
                    "className": "align-middle"
                }, {
                    "targets": [2, 3],
                    "render": (row, type, data) => {
                        return formatRupiah(row);
                    }
                }, {
                    "targets": 4,
                    "searchable": false,
                    "orderable": false,
                    "render": (row, type, data) => {
                        let note = '-';
                        if(data.note){
                            note = `<button class="btn btn-xs btn-info" onclick="loadProductNote('${data.uuid}')">Lihat catatan</button>`;
                        }
                        return `
                            <span>Total Biaya: ${formatRupiah(data.price - data.discount)}/hari</span>
                            <hr class="my-1"/>
                            <span>Catatan: ${note}</span>
                        `;
                    }
                }
            ]
        });
        $("#transaction_accounting-table").DataTable({
            order: [2, 'desc'],
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('adm.json.datatable.transaction.accounting.all', $data->uuid) }}",
                type: "GET",
            },
            success: (result) => {
                console.log(result);
            },
            columns: [
                { "data": "user.name", "name": "user.name" },
                { "data": "amount" },
                { "data": "created_at" },
            ],
            columnDefs: [
                {
                    "targets": "_all",
                    "className": "align-middle"
                }, {
                    "targets": 0,
                    "render": (row, type, data) => {
                        return `
                            <a href="javascript:void(0)">${row}</a>
                        `;
                    }
                }, {
                    "targets": 1,
                    "render": (row, type, data) => {
                        if(data.type == "outcome"){
                            row = 0 - row;
                        }
                        return `
                            <span>${formatRupiah(row)}</span>
                            <hr class="my-1"/>
                            <span class="badge badge-${data.type == 'income' ? 'success' : 'danger'}">Uang ${data.type == 'income' ? 'masuk' : 'keluar'}</span>
                        `;
                    }
                }, {
                    "targets": 2,
                    "render": (row, type, data) => {
                        return moment(row).format('Do MMMM YYYY, HH:mm:ss');
                    }
                }
            ]
        });
    });

    function reloadData(el){
        $("#transaction_item-table").DataTable().ajax.reload();
    }
    function loadProductNote(uuid){
        $.get(`{{ route('adm.json.transaction.item.index', $data->uuid) }}/${uuid}`, (result) => {
            console.log(result);
            let data = result.data;

            $("#modal-transactionItem").find('.item-product').html(data.product.name);
            $("#modal-transactionItem").find('.item-product_sn').html(data.product_detail.serial_number);
            $("#modal-transactionItem").find('.item-price').html(formatRupiah(data.price));
            $("#modal-transactionItem").find('.item-discount').html(formatRupiah(data.discount));
            $("#modal-transactionItem").find('.item-sum_price').html(formatRupiah(data.price - data.discount));
            $("#modal-transactionItem").find('.item-note').html(data.note ?? '-');
            $("#modal-transactionItem").modal('show');
        });      
    }
</script>
@endsection