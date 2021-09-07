@extends('layouts.adm.app', [
    'wsecond_title' => 'Keuangan: Bulanan (Tahun: '.$year.')',
    'sidebar_menu' => 'accounting',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Keuangan: Bulanan (Tahun: '.$year.')',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Tahun: '.$year,
                'is_active' => false,
                'url' => route('adm.accounting.yearly')
            ], [
                'title' => 'Keuangan: Bulanan',
                'is_active' => true,
                'url' => null
            ],
        ]
    ]
])

@section('css_plugins')
    {{-- Datatable --}}
    @include('layouts.adm.partials.plugins.datatable-css')
    {{-- Select2 --}}
    @include('layouts.adm.partials.plugins.select2-css')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Keuangan: Bulanan (Tahun: {{ $year }})</h3>

        <div class="card-tools btn-group">
            <a href="{{ route('adm.accounting.yearly') }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Kembali ke Keuangan: Tahunan">
                <i class="far fa-arrow-alt-circle-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter</h3>

                <div class="card-tools btn-group">
                    <button type="button" class="btn btn-sm btn-secondary" id="btn-reset_filter" disabled>
                        <i class="fas fa-redo-alt mr-1"></i> Reset
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Toko</label>
        
                    <select class="form-control mb-0" id="input-store_id" name="store_id" style="width: 100% !important;">
                    </select>
                </div>
            </div>
        </div>

        <table class="table table-hover table-striped table-bordered" id="accounting_monthly-table">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Nominal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>

        <!-- BAR CHART -->
        <div class="card mb-0 mt-4">
            <div class="card-header">
                <h3 class="card-title">Bar Chart</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart" id="canvas-holder">
                    <canvas id="monthly-chart"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
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
    {{-- Select2 --}}
    @include('layouts.adm.partials.plugins.select2-js')
    {{-- Datatable --}}
    @include('layouts.adm.partials.plugins.datatable-js')
    {{-- Chartjs --}}
    @include('layouts.adm.partials.plugins.chartjs-js')
@endsection

@section('js_inline')
<script>
    $.fn.dataTable.moment( 'dddd, MMMM Do, YYYY' );
    
    const getChartData = () => {
        $.get(`{{ route('adm.json.datatable.accounting.monthly', $year) }}`, (result) => {
            buildChart(result.data);
        });
    }
    const buildChart = (data) => {
        // Empty Container
        $("#monthly-chart").remove();
        $("#canvas-holder").append(`<canvas id="monthly-chart"><canvas>`);

        let labelsData = [];
        let dataset = [];
        let tempData = [];

        // Make sure if data is exists
        if(data.length > 0){
            data.forEach((e) => {
                var month = formatBulan(e.month);
                var sum = 0;

                // Push Year to Label
                labelsData.push(formatBulan(e.month));
                // Get Temp Data
                (e.detail).forEach((data, row) => {
                    let rgbValue = randomRgb();
                    if(data.rgb){
                        rgbValue = data.rgb;
                    }

                    // Push Key
                    tempData.push({
                        'name'                  : data.name,
                        'amount'                : data.amount,
                        'borderColor'           : `rgba(${rgbValue}, 1)`,
                        'backgroundColor'       : `rgba(${rgbValue}, 1)`,
                    });
                    sum += data.amount;
                });

                // tempData.push({
                //     'name'                  : 'Total',
                //     'amount'                : sum,
                //     'backgroundColor'       : 'rgba(210, 214, 222, 1)',
                //     'borderColor'           : 'rgba(210, 214, 222, 1)',
                //     'pointRadius'           : false,
                //     'pointColor'            : 'rgba(210, 214, 222, 1)',
                //     'pointStrokeColor'      : '#c1c7d1',
                //     'pointHighlightFill'    : '#fff',
                //     'pointHighlightStroke'  : 'rgba(220,220,220,1)',
                // });
            });
        }

        // Get Dataset
        let tempDataset = tempData.reduce(function (r, a) {
            r[a.name] = r[a.name] || [];
            r[a.name]['config'] = r[a.name]['config'] || [];
            r[a.name].push(a.amount);

            // Set Config
            r[a.name]['config'].push(a.borderColor);
            r[a.name]['config'].push(a.backgroundColor);
            return r;
        }, Object.create(null));

        // Remap Dataset
        for (const [key, value] of Object.entries(tempDataset)) {
            dataset.push({
                label           : key,
                data            : value,
                borderColor     : value['config'][0],
                backgroundColor : value['config'][1],
                fill            : false,
                lineTension     : 0.3
            });
        }
        let chartData = {
            labels: labelsData,
            datasets: dataset
        }

        //-------------
        //- BAR CHART -
        //-------------
        var barChartData = $.extend(true, {}, chartData);
        console.log(barChartData);
        var barChartOptions = {
            responsive          : true,
            interaction         : {
                intersect   : false,
                mode        : 'index',
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (tooltipItems) => {
                            return `${tooltipItems.dataset.label}: ${formatRupiah(tooltipItems.raw)}`;
                        },
                        footer: (tooltipItems) => {
                            let sum = 0;
                            tooltipItems.forEach(function(tooltipItem) {
                                sum += tooltipItem.parsed.y;
                            });
                            return 'Sum: ' + formatRupiah(sum);
                        },
                    }
                }
            }
        }
        // Chart.Js init
        let lineChart = new Chart(document.getElementById("monthly-chart"), {
            type: 'line',
            data: chartData,
            options: barChartOptions
        });
    }
    const updateFilter = () => {
        let count = 0;
        let filter_toko = $("#input-store_id").val();

        // Count Active Filter
        if(filter_toko != ""){
            count += 1;
        }

        if(count > 0){
            $("#btn-reset_filter").attr('disabled', false);
        } else {
            $("#btn-reset_filter").attr('disabled', true);
        }
    }

    $(document).ready((e) => {
        $("#accounting_monthly-table").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('adm.json.datatable.accounting.monthly', $year) }}",
                type: "GET",
                data: (d) => {
                    d.store_id = $("#input-store_id").val();
                }
            },
            success: (result) => {
                console.log(result);
            },
            columns: [
                { "data": "month" },
                { "data": "amount" },
                { "data": null },
            ],
            columnDefs: [
                {
                    "targets": "_all",
                    "className": "align-middle"
                }, {
                    "targets": 0,
                    "render": (row, type, data) => {
                        return formatBulan(row);
                    }
                }, {
                    "targets": 1,
                    "render": (row, type, data) => {
                        return formatRupiah(row);
                    }
                }, {
                    "targets": 2,
                    "searchable": false,
                    "orderable": false,
                    "render": (row, type, data) => {
                        return `
                            <div class="btn-group">
                                <a href="{{ route('adm.accounting.monthly', $year) }}/${data.month}" class="btn btn-sm btn-primary btn-action">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                            </div>
                        `;
                    }
                }
            ]
        });

        let select2_query = {};
        $("#input-store_id").select2({
            placeholder: 'Filter berdasarkan Toko',
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
        $("#input-store_id").change((e) => {
            $("#accounting_monthly-table").DataTable().ajax.reload();
            updateFilter();
        });

        // Get Chart Data for Chart.js
        getChartData();
    });

    function reloadData(el){
        $("#accounting_monthly-table").DataTable().ajax.reload();
        getChartData();
    }
    
    $("#btn-reset_filter").click((e) => {
        $("#input-store_id").val('').change();
    });
</script>
@endsection