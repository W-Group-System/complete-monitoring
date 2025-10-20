@extends('layouts.header')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="row mt-10 mb-10">
                        <form method='GET' onsubmit='show();' enctype="multipart/form-data" >
                            @csrf
                                <div class="col-md-3">
                                    <label>Year:</label>
                                    <input type="text" id="" name="year" value="{{ Request::get('year') }}" class="form-control year-picker" required>
                                </div>
                                {{-- <div class="col-md-3">
                                    <label>End Date:</label>
                                    <input type="date" name="end_date" value="{{ Request::get('end_date') }}" class="form-control" required>
                                </div> --}}
                                <div class="col-md-1" style="margin-top: 22px">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                        </form>
                        <div class="col-md-1" style="margin-top: 22px">
                            <button class="btn btn-success" onclick="exportTablesToExcel()">Export</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Cottonii Summary</h5>
                </div>
                <div class="ibox-content">
                    <div class="wrapper wrapper-content animated fadeIn">
                        <div class="row">
                            <div class="table-responsive">
                                <table id="table1" class="table table-bordered table-striped table-hover tablewithSearch">
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="text-center" style="color: black; background-color: rgb(7, 133, 7);">Supplier</th>
                                            @php
                                                $months = [
                                                    'January', 'February', 'March', 'April', 'May', 'June', 
                                                    'July', 'August', 'September', 'October', 'November', 'December'
                                                ];
                                            @endphp
                                            @foreach ($months as $month)
                                            <th colspan="3" class="text-center" style="color: black; background-color: rgb(7, 133, 7);">
                                                {{ $month }}
                                            </th>
                                            @endforeach
                                            <th class="text-center text-success">Total Cottoni</th> 
                                        </tr>
                                        <tr>
                                            <th>Supplier</th>
                                            <th></th>
                                            @foreach ($months as $month)
                                                <th class="text-center">Del Price</th>
                                                <th class="text-center">Buying Price</th>
                                                <th class="text-center">Cottoni</th>
                                            @endforeach
                                            <th></th>
                                        </tr>
                                    </thead>
                                    @php
                                        $monthlyTotals = [];
                                    @endphp
                                    <tbody>
                                        @foreach ($suppliers as $supplier)
                                        @php
                                           $totalCottoniPerSupplier = 0;
                                        @endphp
                                        <tr>
                                            <td style="
                                            background-color:
                                                @if(optional($supplier)->OriginGroup == 'ZAMBO') skyblue;
                                                @elseif(optional($supplier)->OriginGroup == 'PALAWAN') peachpuff;
                                                @elseif(optional($supplier)->OriginGroup == 'MINDORO') pink;
                                                @elseif(optional($supplier)->OriginGroup == 'IMPORT') orange;
                                                @elseif(optional($supplier)->OriginGroup == 'ANTIQUE') GREEN;
                                                @else black;
                                                @endif
                                            color:
                                                @if(optional($supplier)->OriginGroup == 'OTHERS' || empty(optional($supplier)->OriginGroup)) white;
                                                @else black;
                                                @endif
                                            ">
                                            {{ optional($supplier)->Name }}</td>
                                            <td style="
                                                background-color:
                                                    @if(optional($supplier)->OriginGroup == 'ZAMBO') skyblue;
                                                    @elseif(optional($supplier)->OriginGroup == 'PALAWAN') peachpuff;
                                                    @elseif(optional($supplier)->OriginGroup == 'MINDORO') pink;
                                                    @elseif(optional($supplier)->OriginGroup == 'IMPORT') orange;
                                                    @elseif(optional($supplier)->OriginGroup == 'ANTIQUE') GREEN;
                                                    @else black;
                                                    @endif
                                                color:
                                                    @if(optional($supplier)->OriginGroup == 'OTHERS' || empty(optional($supplier)->OriginGroup)) white;
                                                    @else black;
                                                    @endif
                                            ">
                                                {{ optional($supplier)->OriginGroup }}
                                            </td>
                                                @foreach ($months as $month)
                                                    @php
                                                        $monthNum = date('m', strtotime($month));
                                                        $grpos = $supplier->opdn->filter(function($grpo) use ($monthNum) {
                                                            $docDate = \Carbon\Carbon::parse($grpo->DocDate);
                                                            $firstLine = $grpo->grpoLines->first();
                                                           
                                                            // return $docDate->format('m') == $monthNum && optional($firstLine)->ItemCode === 'Seaweeds-COTTONII';
                                                            return $docDate->format('m') == $monthNum && in_array(optional($firstLine)->ItemCode, ['Seaweeds-COTTONII', 'SWDCOTPHIL']);
                                                        });
                                                        $totalArrivalWt = 0;
                                                        $totalWeightedAmount = 0;
                                                        $totalLabMc = 0;
                                                        $totalFreightPo = 0;
                                                        $totalTruckingPo = 0;
                                                        $deductionPhp = 0;
                                                        $delPrice = 0;
                                                        foreach ($grpos as $grpo) {
                                                            foreach ($grpo->freightPoInvoice as $invoice) {
                                                                foreach ($invoice->DeductionLines as $freightLine) {
                                                                    $vat = $freightLine->VatPrcnt / 100;
                                                                    $vatProduct = ($freightLine->LineTotal * $vat);
                                                                    $totalFreightPo += ($freightLine->LineTotal + $vatProduct);
                                                                }
                                                            }
                                        
                                                            foreach ($grpo->truckingPoInvoice as $truckInvoice) {
                                                                foreach ($truckInvoice->DeductionLines as $truckLine) {
                                                                    $vat = $truckLine->VatPrcnt / 100;
                                                                    $vatProduct = ($truckLine->LineTotal * $vat);
                                                                    $totalTruckingPo += ($truckLine->LineTotal + $vatProduct);
                                                                }
                                                            }
                                                            $totalDeductionPhp = 0;
                                                            foreach ($grpo->grpoLines as $line) {
                                                                $qty = (float) $line->Quantity;
                                                                $price =(float) $line->Price;
                                        
                                                                $totalArrivalWt += $qty;
                                                                $totalWeightedAmount += $qty * $price;
                                        
                                                                $moistureData = optional(
                                                                    optional($grpo->quality_created_approved)->chemical_testings
                                                                )->first(function ($item) {
                                                                    return stripos($item->parameter, 'moisture') !== false;
                                                                });

                                                                $moisture = (float) optional($moistureData)->result;

                                                                $avg2 = (float) ($grpo->U_Average2 ?? 0);
                                                                $totalLabMc += $qty * $moisture;
                                        
                                                                $mcDeduction = optional($grpo->qualityResult)->moistureResult - ($grpo->U_Average2 ?? 0);
                                                                $deductionPerLine = ($mcDeduction / 100) * $line->Price;
                                                                $totalDeductionPhp += $deductionPerLine * $line->Quantity;

                                                                // $delPrice = $totalArrivalWt
                                                                // ? number_format(
                                                                //     ($price - max($deductionPhp, 0)) +
                                                                //     (($totalFreightPo + $totalTruckingPo) / $totalArrivalWt),
                                                                //     2
                                                                // )
                                                                // : null;
                                                            }
                                                            $avgDeductionPhp = $totalArrivalWt > 0 ? $totalDeductionPhp / $totalArrivalWt : 0;
                                                        }
                                        
                                                        if ($totalArrivalWt) {
                                                            $avgPrice = $totalWeightedAmount / $totalArrivalWt;
                                                            $delPrice = number_format(
                                                                ($avgPrice - max($avgDeductionPhp, 0))
                                                                + (($totalFreightPo + $totalTruckingPo) / $totalArrivalWt),
                                                                2
                                                            );
                                                            $buyingPrice = number_format($totalWeightedAmount / $totalArrivalWt, 2);
                                                            $cottoni = number_format( $totalArrivalWt, 2);
                                                            $totalCottoniPerSupplier += $totalArrivalWt;
                                                        } else {
                                                            $delPrice = $buyingPrice = $cottoni = '-';
                                                        }
                                                                if (!isset($monthlyTotals[$monthNum])) {
                                                                    $monthlyTotals[$monthNum] = [
                                                                        'totalCottoni' => 0,
                                                                        'totalDeliveryAmount' => 0,
                                                                        'totalBuyingAmount' => 0,
                                                                    ];
                                                                }

                                                                $monthlyTotals[$monthNum]['totalCottoni'] += $totalArrivalWt;
                                                                $monthlyTotals[$monthNum]['totalDeliveryAmount'] += $totalArrivalWt * (float) $delPrice;
                                                                $monthlyTotals[$monthNum]['totalBuyingAmount'] += $totalArrivalWt * (float) $buyingPrice;
                                                    @endphp
                                        
                                                    <td class="text-center">{{ $delPrice }}</td>
                                                    <td class="text-center">{{ $buyingPrice }}</td>
                                                    <td class="text-center">{{ $cottoni }}</td>
                                                @endforeach
                                                <td class="text-center text-success font-weight-bold">{{ number_format($totalCottoniPerSupplier, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot style="background-color:black; color: white;">
                                        <td colspan="2" class="text-end">Average Delivered Price:</td>
                                        @php $totalAvgCottoni  = 0; @endphp
                                        @foreach ($months as $month)
                                            @php
                                                $monthNum = date('m', strtotime($month));
                                                $avgDelivery = '-';
                                                $avgBuying = '-';
                                                $avgCottoni = '-';
                                                if (isset($monthlyTotals[$monthNum]) && $monthlyTotals[$monthNum]['totalCottoni'] > 0) {
                                                    $avgDelivery = $monthlyTotals[$monthNum]['totalCottoni'] > 0 ? number_format(
                                                        $monthlyTotals[$monthNum]['totalDeliveryAmount'] / $monthlyTotals[$monthNum]['totalCottoni'],
                                                        2
                                                    ) : '-';

                                                    $avgBuying = $monthlyTotals[$monthNum]['totalCottoni'] > 0 ? number_format(
                                                        $monthlyTotals[$monthNum]['totalBuyingAmount'] / $monthlyTotals[$monthNum]['totalCottoni'],
                                                        2
                                                    ) : '-';
                                                    $avgCottoni = $monthlyTotals[$monthNum]['totalCottoni'] > 0
                                                        ? number_format($monthlyTotals[$monthNum]['totalCottoni'], 2)
                                                        : '-';

                                                    if ($monthlyTotals[$monthNum]['totalCottoni'] > 0) {
                                                        $totalAvgCottoni += $monthlyTotals[$monthNum]['totalCottoni'];
                                                    }
                                                }
                                            @endphp
                                            <td class="text-center">{{ $avgDelivery }}</td> 
                                            <td class="text-center">{{ $avgBuying }}</td> 
                                            <td class="text-center">{{ $avgCottoni }}</td> 
                                        @endforeach
                                        <td class="text-center" colspan="3">{{ number_format($totalAvgCottoni, 2) }}</td> 
                                    </tfoot>
                                </table>
                            </div> 
                        </div>
                    </div>   
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>SUMMARY BUYING </h5>
                </div>
                <div class="ibox-content">
                    <div class="wrapper wrapper-content animated fadeIn">
                        <div class="row">
                            <div class="table-responsive">
                                <table id="table2" class="table table-bordered table-striped table-hover tablewithSearch">
                                    <thead>
                                        <tr>
                                            <th class="text-center"></th>
                                            <th class="text-center">DEL PRICE</th>
                                            <th class="text-center">BUYING PRICE</th>
                                            <th class="text-center">COTTONII</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php 
                                            $newTotalCottoni  = 0;
                                            $newTotalDelPrice  = 0;
                                            $newTotalBuyingPrice  = 0;
                                        @endphp
                                        @foreach ($months as $month)
                                        @php
                                            $monthNum = date('m', strtotime($month));
                                            $avgDelivery = '-';
                                            $avgBuying = '-';
                                            $avgCottoni = '-';
                                            $buyingCottoni = '-';

                                            if (isset($monthlyTotals[$monthNum]) && $monthlyTotals[$monthNum]['totalCottoni'] > 0) {
                                                $rawAvgDelivery = $monthlyTotals[$monthNum]['totalDeliveryAmount'] / $monthlyTotals[$monthNum]['totalCottoni'];
                                                $rawAvgBuying = $monthlyTotals[$monthNum]['totalBuyingAmount'] / $monthlyTotals[$monthNum]['totalCottoni'];
                                                $rawAvgCottoni = $monthlyTotals[$monthNum]['totalCottoni'];

                                                $avgDelivery = number_format($rawAvgDelivery, 2);
                                                $avgBuying = number_format($rawAvgBuying, 2);
                                                $avgCottoni = number_format($rawAvgCottoni, 2);
                                                $newTotalCottoni += $rawAvgCottoni;

                                                $buyingCottoni = number_format($rawAvgBuying * $rawAvgCottoni, 2);
                                                $newTotalDelPrice += ($rawAvgCottoni * $rawAvgDelivery);
                                                $newTotalBuyingPrice += ($rawAvgCottoni * $rawAvgBuying);
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $month }}</td>
                                            <td class="text-center">{{ $avgDelivery }}</td> 
                                            <td class="text-center">{{ $avgBuying }}</td> 
                                            <td class="text-center">{{ $avgCottoni }}</td> 
                                            <td class="text-center">
                                                {{ number_format(floatval(str_replace(',', '', $avgBuying)) * floatval(str_replace(',', '', $avgCottoni)), 2) }}
                                            </td>
                                            {{-- <td>{{ $buyingCottoni }}</td> --}}
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot style="background-color:black; color: white;">
                                        <td></td>
                                        <td class="text-center">
                                            {{ $newTotalCottoni > 0 ? number_format($newTotalDelPrice / $newTotalCottoni, 2) : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $newTotalCottoni > 0 ? number_format($newTotalBuyingPrice / $newTotalCottoni, 2) : '-' }}
                                        </td> 
                                        <td class="text-center">
                                            {{ number_format($newTotalCottoni, 2) }}
                                        </td>
                                        <td class="text-center"></td> 
                                    </tfoot>
                                </table>
                            </div> 
                        </div>
                    </div>   
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Per Supplier </h5>
                </div>
                <div class="ibox-content">
                    <div class="wrapper wrapper-content animated fadeIn">
                        <div class="row">
                            <div style="max-width: 1000px; width: 100%; margin: auto;">
                                <canvas style="max-width: 1000px; width: 100%; margin: auto;" id="supplierCottoniPie"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Per Area </h5>
                </div>
                <div class="ibox-content">
                    <div class="wrapper wrapper-content animated fadeIn">
                        <div class="row">
                            <div style="max-width: 1000px; width: 100%; margin: auto;">
                                <canvas id="areaCottoniPie"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    const pieLabels = [];
    const pieData = [];
    const areaCottoni = {};
    const predefinedColors = [
        'hsl(0, 70%, 60%)',     // Red
        'hsl(36, 70%, 60%)',    // Orange
        'hsl(60, 70%, 60%)',    // Yellow
        'hsl(120, 70%, 60%)',   // Green
        'hsl(180, 70%, 60%)',   // Cyan
        'hsl(210, 70%, 60%)',   // Sky Blue
        'hsl(240, 70%, 60%)',   // Blue
        'hsl(275, 70%, 60%)',   // Purple
        'hsl(310, 70%, 60%)',   // Magenta
        'hsl(330, 70%, 60%)'    // Pink
        ];
    @php
        $supplierQuantities = [];

        foreach ($suppliers as $supplier) {
            $totalCottoni = 0;

            foreach ($months as $month) {
                $monthNum = date('m', strtotime($month));
                $grpos = $supplier->opdn->filter(function($grpo) use ($monthNum) {
                    $docDate = \Carbon\Carbon::parse($grpo->DocDate);
                    $firstLine = $grpo->grpoLines->first();
                    return $docDate->format('m') == $monthNum && in_array(optional($firstLine)->ItemCode, ['Seaweeds-COTTONII', 'SWDCOTPHIL']);
                });

                foreach ($grpos as $grpo) {
                    foreach ($grpo->grpoLines as $line) {
                        $totalCottoni += (float) $line->Quantity;
                    }
                }
            }

            if ($totalCottoni > 0) {
                $supplierQuantities[] = [
                    'name' => $supplier->Name,
                    'quantity' => $totalCottoni,
                    'area' => $supplier->OriginGroup,
                ];
            }
        }

        $topSuppliers = collect($supplierQuantities)->sortByDesc('quantity')->take(10);
    @endphp

    @foreach ($topSuppliers as $supplier)
        pieLabels.push(@json($supplier['name']));
        pieData.push({{ $supplier['quantity'] }});

        if (!areaCottoni[@json($supplier['area'])]) {
            areaCottoni[@json($supplier['area'])] = 0;
        }
        areaCottoni[@json($supplier['area'])] += {{ $supplier['quantity'] }};
    @endforeach

    const newColors = [
    'hsl(0, 70%, 60%)',     // Red
    'hsl(36, 70%, 60%)',    // Orange
    'hsl(60, 70%, 60%)',    // Yellow
    'hsl(120, 70%, 60%)',   // Green
    'hsl(180, 70%, 60%)',   // Cyan
    'hsl(210, 70%, 60%)',   // Sky Blue
    'hsl(240, 70%, 60%)',   // Blue
    'hsl(275, 70%, 60%)',   // Purple
    'hsl(310, 70%, 60%)',   // Magenta
    'hsl(330, 70%, 60%)'    // Pink
    ];

    const pieColors = pieLabels.map((_, index) =>
    newColors[index % newColors.length]
    );
    const pieConfig = {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                label: 'Total Cottoni (kg) per Supplier',
                data: pieData,
                backgroundColor: pieColors,
                borderColor: 'white',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    
                },
                title: {
                    display: true,
                    // text: 'Top 10 Suppliers - Total Cottoni (Annual)'
                },
                datalabels: {
                    formatter: (value, context) => {
                        const label = context.chart.data.labels[context.dataIndex];
                        const data = context.chart.data.datasets[0].data;
                        const total = data.reduce((sum, val) => sum + val, 0);
                        const percentage = (value / total * 100).toFixed(1);
                        return `${label}\n${percentage}%`;
                    },
                    color: 'black',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    // align: 'end',
                    anchor:'end',
                }
            }
        },
        plugins: [ChartDataLabels]
    };

    new Chart(document.getElementById('supplierCottoniPie'), pieConfig);

    const areaLabels = Object.keys(areaCottoni);
    const areaData = Object.values(areaCottoni);
    
    const areaColors = areaLabels.map((_, index) =>
        predefinedColors[index % predefinedColors.length]
    );
    const pieConfigByArea = {
        type: 'pie',
        data: {
            labels: areaLabels,
            datasets: [{
                label: 'Total Cottoni (kg) by Area',
                data: areaData,
                backgroundColor: areaColors,
                borderColor: 'white',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    // text: 'Total Cottoni per Area (Annual)'
                },
                datalabels: {
                    formatter: (value, context) => {
                        const label = context.chart.data.labels[context.dataIndex];
                        const data = context.chart.data.datasets[0].data;
                        const total = data.reduce((sum, val) => sum + val, 0);
                        const percentage = (value / total * 100).toFixed(1);
                        return `${label}\n${percentage}%`;
                    },
                    color: 'black',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    // align: 'end',
                    anchor: 'end',
                }
            }
        },
        plugins: [ChartDataLabels]
    };

    new Chart(document.getElementById('areaCottoniPie'), pieConfigByArea);

    function exportTablesToExcel() {
        const wb = XLSX.utils.book_new();

        const table1 = document.getElementById('table1');
        const ws1 = XLSX.utils.table_to_sheet(table1);
        XLSX.utils.book_append_sheet(wb, ws1, 'Spinosum Summary');

        const table2 = document.getElementById('table2');
        const ws2 = XLSX.utils.table_to_sheet(table2);
        XLSX.utils.book_append_sheet(wb, ws2, 'Summary Buying');

        XLSX.writeFile(wb, 'report.xlsx');
    }
</script>
@endsection

