@extends('layouts.header')

@section('content')

<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">

        <div class="col-lg-12">
            <div class="ibox">

                <div class="ibox-title">
                    <h5>Monthly Spinosum Summary</h5>
                </div>

                <div class="ibox-content">

                    <form method="GET">

                        <div class="row">

                            <div class="col-md-2">
                                <label>Company</label>

                                <select class="chosen-select form-control" name="company">
                                    <option value="All" {{ request('company')=='All' ? 'selected' : '' }}>All</option>
                                    <option value="WHI" {{ request('company')=='WHI' ? 'selected' : '' }}>WHI</option>
                                    <option value="CCC" {{ request('company')=='CCC' ? 'selected' : '' }}>CCC</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>From Month</label>

                                <input
                                    type="month"
                                    class="form-control"
                                    name="from_month"
                                    value="{{ request('from_month') }}">
                            </div>

                            <div class="col-md-2">
                                <label>To Month</label>

                                <input
                                    type="month"
                                    class="form-control"
                                    name="to_month"
                                    value="{{ request('to_month') }}">
                            </div>

                            <div class="col-md-2" style="margin-top:25px;">
                                <button class="btn btn-primary">
                                    Filter
                                </button>
                            </div>

                        </div>

                    </form>

                </div>

            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <button class="btn btn-success" onclick="exportTablesToExcel()">Export</button>
                </div>
            </div>
        </div>

        <div class="col-lg-12">

            <div class="ibox">

                <div class="ibox-title">
                    <h5>Monthly Summary</h5>
                </div>

                <div class="ibox-content">

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped" id="table1">

                            <thead>

                            <tr>
                                <th>Month</th>
                                <th>CAR</th>
                                <th>CCC</th>
                                {{-- <th>PBI</th> --}}
                                <th>Total Qty</th>
                                <th>Monthly Delivered Price</th>
                            </tr>

                            </thead>

                            <tbody>

                            @php

                                $grandCar=0;
                                $grandCCC=0;
                                // $grandPBI=0;
                                $grandQty=0;
                                $grandAmount=0;

                            @endphp

                            @foreach($report as $row)

                                @php

                                    $grandCar += $row['CAR'];
                                    $grandCCC += $row['CCC'];
                                    // $grandPBI += $row['PBI'];

                                    $grandQty += $row['TotalQty'];

                                    $grandAmount += $row['TotalAmount'];

                                @endphp

                                <tr>

                                    <td>{{ substr($row['month'],0,3) }}</td>

                                    <td>{{ number_format($row['CAR'],2) }}</td>

                                    <td>{{ number_format($row['CCC'],2) }}</td>

                                    {{-- <td>{{ number_format($row['PBI'],2) }}</td> --}}

                                    <td>{{ number_format($row['TotalQty'],2) }}</td>

                                    <td>{{ number_format($row['AveragePrice'],2) }}</td>

                                </tr>

                            @endforeach

                            </tbody>

                            <tfoot>

                                <tr style="background:#f5f5f5;font-weight:bold">

                                    <td>Total</td>

                                    <td>{{ number_format($grandCar,2) }}</td>

                                    <td>{{ number_format($grandCCC,2) }}</td>

                                    {{-- <td>{{ number_format($grandPBI,2) }}</td> --}}

                                    <td>{{ number_format($grandQty,2) }}</td>

                                    <td>
                                        {{ number_format($grandQty ? $grandAmount/$grandQty : 0,2) }}
                                    </td>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>

        </div>


         <div class="col-lg-12">

            <div class="ibox">

                <div class="ibox-title">
                    <h5>Monthly Summary</h5>
                </div>

                <div class="ibox-content">

                    <div class="flot-chart">
                        <div class="flot-chart-content"
                             id="monthlyChart">
                        </div>
                    </div>

                    <div style="margin-top: 20px;">
                        <div style="justify-content: center; display:flex;" id="spilegend-container"></div>
                    </div>

                    

                </div>

            </div>

        </div>
    </div>
</div>

@php

$carData = [];
$cccData = [];
// $pbiData = [];
$priceData = [];
$monthTicks = [];

$i = 0;

foreach($report as $row){

    $monthTicks[] = [$i, substr($row['month'],0,3)];

    $carData[] = [$i, (float) $row['CAR']];
    $cccData[] = [$i, (float) $row['CCC']];
    // $pbiData[] = [$i, (float) $row['PBI']];
    $priceData[] = [$i, (float) $row['AveragePrice']];

    $i++;

}

@endphp

<script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>

    function exportTablesToExcel() {
        const wb = XLSX.utils.book_new();
    
        const table1 = document.getElementById('table1');
        const ws1 = XLSX.utils.table_to_sheet(table1);
        XLSX.utils.book_append_sheet(wb, ws1, 'Sheet1');
    
        // const table2 = document.getElementById('table2');
        // const ws2 = XLSX.utils.table_to_sheet(table2);
        // XLSX.utils.book_append_sheet(wb, ws2, 'Sheet2');
    
        XLSX.writeFile(wb, 'report.xlsx');
    }

    $(function () {

        var dataset = [

            {
                label: "CAR",
                data: @json($carData),
                color: "#00B050",
                stack: true,
                bars: {
                    show: true,
                    align: "center",
                    barWidth: 0.6,
                    lineWidth: 0,
                    fill: true,
                    fillColor: "#00B050"
                }
            },

            {
                label: "CCC",
                data: @json($cccData),
                color: "#FF0000",
                stack: true,
                bars: {
                    show: true,
                    align: "center",
                    barWidth: 0.6,
                    lineWidth: 0,
                    fill: true,
                    fillColor: "#FF0000"
                }
            },

        

            {
                label: "Monthly Delivered Price",
                data: @json($priceData),
                color: "#000000",
                yaxis: 2,
                stack: null,
                lines: {
                    show: true,
                    lineWidth: 2
                },
                points: {
                    show: true,
                    radius: 4,
                    fill: true
                }
            }

        ];

        var options = {

            series: {
                stack: true
            },

            xaxis: {
                ticks: @json($monthTicks),
                tickLength: 0
            },

            yaxes: [
                {
                    position: "left",
                    min: 0
                },
                {
                    position: "right",
                    min: 0
                }
            ],

            legend: {
                container: $("#spilegend-container"),
                noColumns: 4
            },

            grid: {
                hoverable: true,
                clickable: true,
                borderWidth: 0
            }

        };
        var plot = $.plot($("#monthlyChart"), dataset, options);

        // Tooltip
        $("<div id='tooltip'></div>").css({
            position: "absolute",
            display: "none",
            border: "1px solid #fdd",
            padding: "6px",
            "background-color": "#fee",
            opacity: 0.90
        }).appendTo("body");

        $("#monthlyChart").bind("plothover", function (event, pos, item) {

            if (item) {

                var x = item.datapoint[0];
                var y;

                if (item.series.bars.show) {
                    y = item.datapoint[1] - item.datapoint[2];
                } else {
                    y = item.datapoint[1];
                }

                $("#tooltip")
                    .html(
                        item.series.label +
                        "<br>" +
                        @json(array_column($monthTicks,1))[x] +
                        "<br>" +
                        Number(y).toLocaleString(undefined,{
                            minimumFractionDigits:2,
                            maximumFractionDigits:2
                        })
                    )
                    .css({
                        top: item.pageY + 5,
                        left: item.pageX + 5
                    })
                    .fadeIn(200);

            } else {

                $("#tooltip").hide();

            }

        });

    });

</script>

@endsection