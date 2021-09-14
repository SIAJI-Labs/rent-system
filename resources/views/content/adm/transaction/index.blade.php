@extends('layouts.adm.app', [
    'wsecond_title' => 'Daftar Transaksi',
    'sidebar_menu' => 'transaction',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Daftar Transaksi',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Transaksi',
                'is_active' => true,
                'url' => false
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
        <h3 class="card-title">Daftar Transaksi</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.transaction.create') }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Tambah Baru">
                <i class="fas fa-plus mr-1"></i> Tambah Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered" id="transaction-table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Kostumer</th>
                    <th>Tanggal Sewa</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>

        <div class="card mb-0 mt-4 collapsed-card">
            <div class="card-header">
                <h3 class="card-title">Legend</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" style="display: none;">
                <div>
                    <span class="badge bg-secondary"><small class="text-secondary">XX</small></span>
                    <span><span class="badge badge-warning">Booking</span> Transaksi belum diproses dan sudah melebihi awal periode sewa!</span>
                </div>
                <div>
                    <span class="badge bg-primary"><small class="text-primary">XX</small></span>
                    <span><span class="badge badge-warning">Booking</span> Transaksi mendekati awal periode sewa!</span>
                </div>
                <div>
                    <span class="badge bg-warning"><small class="text-warning">XX</small></span>
                    <span><span class="badge badge-primary">Proses</span> Transaksi mendekati batas periode sewa yang telah ditentukan!</span>
                </div>
                <div>
                    <span class="badge bg-danger"><small class="text-danger">XX</small></span>
                    <span><span class="badge badge-primary">Proses</span> Transaksi melebihi periode sewa yang telah ditentukan!</span>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="button" class="btn btn-sm btn-secondary" onclick="reloadData($(this))">
            <i class="fas fa-sync-alt mr-1"></i> Muat Ulang</button>
    </div>
    <!-- /.card-footer-->
</div>
@endsection

@section('js_plugins')
    {{-- Datatable --}}
    @include('layouts.adm.partials.plugins.datatable-js')
@endsection

@section('js_inline')
<script>
    $.fn.dataTable.moment( 'dddd, MMMM Do, YYYY' );
    
    $(document).ready((e) => {
        $("#transaction-table").DataTable({
            order: [0, 'desc'],
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('adm.json.datatable.transaction.all') }}",
                type: "GET",
            },
            success: (result) => {
                console.log(result);
            },
            columns: [
                { "data": "invoice" },
                { "data": "customer.name", "name": "customer.name" },
                { "data": "start_date" },
                { "data": "end_date" },
                { "data": "status" },
                { "data": null },
            ],
            columnDefs: [
                {
                    "targets": "_all",
                    "className": "align-middle"
                }, {
                    "targets": 0,
                    "render": (row, type, data) => {
                        return `
                            <span>${row}</span>
                            <hr class="my-1"/>
                            <small>Transaksi pada: ${moment(data.created_at).format('Do MMMM YYYY, HH:mm')}/ #${data.id}</small>
                        `;
                    }
                }, {
                    "targets": 1,
                    "render": (row, type, data) => {
                        return `
                            <a href="{{ route('adm.customer.index') }}/${data.customer.uuid}">${data.customer.name}</a>
                        `;
                    }
                }, {
                    "targets": [2, 3],
                    "render": (row, type, data) => {
                        return moment(row).format("Do MMMM YYYY, HH:mm");
                    }
                }, {
                    "targets": 4,
                    "render": (row, type, data) => {
                        let colorBg = 'primary';
                        switch(row){
                            case 'booking':
                                colorBg = 'warning';
                                break;
                            case 'complete':
                                colorBg = 'success';
                                break;
                            case 'cancel':
                                colorBg = 'danger';
                                break;
                        }
                        return `<span class="badge badge-${colorBg}">${ucwords(row)}</span>`;
                    }
                }, {
                    "targets": 5,
                    "searchable": false,
                    "orderable": false,
                    "render": (row, type, data) => {
                        return `
                            <div class="btn-group">
                                <a href="{{ route('adm.transaction.index') }}/${data.uuid}" class="btn btn-sm btn-primary btn-action">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                <a href="{{ route('adm.transaction.index') }}/${data.uuid}/edit" class="btn btn-sm btn-warning btn-action">
                                    <i class="far fa-edit mr-1"></i> Edit
                                </a>
                            </div>
                        `;
                    }
                }
            ],
            createdRow: ( row, data, dataIndex) => {
                let currDate = moment();
                let startDate = moment(data.start_date);
                let endDate = moment(data.end_date);
                
                if(data.status == 'process'){
                    if(moment(currDate).format('Do-MM-YYYY') == moment(endDate).format('Do-MM-YYYY') && moment(currDate).format("HH:mm:ss") < moment(endDate).format('HH:mm:ss')){
                        $(row).addClass('table-warning');
                    } else if(currDate > endDate){
                        $(row).addClass('table-danger');
                    }
                } else if (data.status == 'booking'){
                    if(moment(currDate).format('Do-MM-YYYY') == moment(startDate).format('Do-MM-YYYY') && moment(currDate).format("HH:mm:ss") < moment(startDate).format('HH:mm:ss')){
                        $(row).addClass('table-primary');
                    } else if(currDate > startDate){
                        $(row).addClass('table-secondary');
                    }
                }
            }
        });
    });

    function reloadData(el){
        $("#transaction-table").DataTable().ajax.reload();
    }
</script>
@endsection