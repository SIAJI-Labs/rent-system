@extends('layouts.adm.app', [
    'wsecond_title' => 'Keuangan: Tahunan',
    'sidebar_menu' => 'accounting',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Keuangan: Tahunan',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('adm.index')
            ], [
                'title' => 'Keuangan: Tahunan',
                'is_active' => true,
                'url' => null
            ],
        ]
    ]
])

@section('css_plugins')
    {{-- Datatable --}}
    @include('layouts.adm.partials.plugins.datatable-css')
    {{-- Chartjs --}}
    {{-- @include('layouts.adm.partials.plugins.chartjs-css') --}}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Keuangan: Tahunan</h3>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered" id="accounting-table">
            <thead>
                <tr>
                    <th>Tahun</th>
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
                    <canvas id="yearly-chart"></canvas>
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
    {{-- Datatable --}}
    @include('layouts.adm.partials.plugins.datatable-js')
    {{-- Chartjs --}}
    @include('layouts.adm.partials.plugins.chartjs-js')
@endsection

@section('js_inline')
<script>
    $.fn.dataTable.moment( 'dddd, MMMM Do, YYYY' );
    
    const getChartData = () => {
        $.get(`{{ route('adm.json.datatable.accounting.yearly') }}`, (result) => {
            buildChart(result.data);
        });
    }
    const buildChart = (data) => {
        // Empty Container
        $("#yearly-chart").remove();
        $("#canvas-holder").append(`<canvas id="yearly-chart"><canvas>`);

        let labelsData = [];
        let dataset = [];
        let tempData = [];

        // Make sure if data is exists
        if(data.length > 0){
            data.forEach((e) => {
                var year = e.year;
                var sum = 0;

                // Push Year to Label
                labelsData.push(e.year);
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
        let lineChart = new Chart(document.getElementById("yearly-chart"), {
            type: 'line',
            data: chartData,
            options: barChartOptions
        });
    }

    $(document).ready((e) => {
        $("#accounting-table").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('adm.json.datatable.accounting.yearly') }}",
                type: "GET",
            },
            success: (result) => {
                console.log(result);
            },
            columns: [
                { "data": "year" },
                { "data": "amount" },
                { "data": null },
            ],
            columnDefs: [
                {
                    "targets": "_all",
                    "className": "align-middle"
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
                                <a href="{{ route('adm.accounting.yearly') }}/${data.year}" class="btn btn-sm btn-primary btn-action">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                            </div>
                        `;
                    }
                }
            ]
        });

        // Get Chart Data for Chart.js
        getChartData();
    });

    function reloadData(el){
        $("#accounting-table").DataTable().ajax.reload();
        getChartData();
    }
</script>
@endsection