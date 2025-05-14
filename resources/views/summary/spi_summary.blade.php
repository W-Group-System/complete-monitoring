@extends('layouts.header')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form method='GET' onsubmit='show();' enctype="multipart/form-data" >
                        @csrf
                        <div class="row mt-10 mb-10">
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Spinosum Summary</h5>
                </div>
                <div class="ibox-content">
                    <div class="wrapper wrapper-content animated fadeIn">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover tablewithSearch">
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
                                            <th class="text-center text-success">Total Spinosum</th> 
                                        </tr>
                                        <tr>
                                            <th>Supplier</th>
                                            <th></th>
                                            @foreach ($months as $month)
                                                <th class="text-center">Del Price</th>
                                                <th class="text-center">Buying Price</th>
                                                <th class="text-center">Spinosum</th>
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
                                                @if($supplier->OriginGroup == 'ZAMBO') skyblue;
                                                @elseif($supplier->OriginGroup == 'PALAWAN') peachpuff;
                                                @elseif($supplier->OriginGroup == 'MINDORO') pink;
                                                @elseif($supplier->OriginGroup == 'IMPORT') orange;
                                                @elseif($supplier->OriginGroup == 'ANTIQUE') GREEN;
                                                @else black;
                                                @endif
                                            color:
                                                @if($supplier->OriginGroup == 'OTHERS' || empty($supplier->OriginGroup)) white;
                                                @else black;
                                                @endif
                                            ">
                                            {{ $supplier->CardName }}</td>
                                            <td style="
                                                background-color:
                                                    @if($supplier->OriginGroup == 'ZAMBO') skyblue;
                                                    @elseif($supplier->OriginGroup == 'PALAWAN') peachpuff;
                                                    @elseif($supplier->OriginGroup == 'MINDORO') pink;
                                                    @elseif($supplier->OriginGroup == 'IMPORT') orange;
                                                    @elseif($supplier->OriginGroup == 'ANTIQUE') GREEN;
                                                    @else black;
                                                    @endif
                                                color:
                                                    @if($supplier->OriginGroup == 'OTHERS' || empty($supplier->OriginGroup)) white;
                                                    @else black;
                                                    @endif
                                            ">
                                                {{ $supplier->OriginGroup }}
                                            </td>
                                                @foreach ($months as $month)
                                                    @php
                                                        $monthNum = date('m', strtotime($month));
                                                        $grpos = $supplier->opdn->filter(function($grpo) use ($monthNum) {
                                                            $docDate = \Carbon\Carbon::parse($grpo->DocDate);
                                                            $firstLine = $grpo->grpoLines->first();
                                                           
                                                            return $docDate->format('m') == $monthNum && optional($firstLine)->ItemCode === 'SWDSPIPHIL';
                                                        });
                                                        $totalArrivalWt = 0;
                                                        $totalWeightedAmount = 0;
                                                        $totalLabMc = 0;
                                                        $totalFreightPo = 0;
                                                        $totalTruckingPo = 0;
                                                        $deductionPhp = 0;
                                                        $delPrice = null;
                                                        foreach ($grpos as $grpo) {
                                                            foreach ($grpo->freightPoInvoice as $invoice) {
                                                                foreach ($invoice->DeductionLines as $freightLine) {
                                                                    $vat = $freightLine->VatPrcnt / 100;
                                                                    $totalFreightPo += $freightLine->LineTotal * (1 + $vat);
                                                                }
                                                            }
                                        
                                                            foreach ($grpo->truckingPoInvoice as $truckInvoice) {
                                                                foreach ($truckInvoice->DeductionLines as $truckLine) {
                                                                    $vat = $truckLine->VatPrcnt / 100;
                                                                    $totalTruckingPo += $truckLine->LineTotal * (1 + $vat);
                                                                }
                                                            }
                                        
                                                            foreach ($grpo->grpoLines as $line) {
                                                                $qty = $line->Quantity;
                                                                $price = $line->Price;
                                        
                                                                $totalArrivalWt += $line->Quantity;
                                                                $totalWeightedAmount += $line->Quantity * $line->Price;
                                        
                                                                $moisture = optional($grpo->qualityResult)->U_MOIST ?? 0;
                                                                $avg2 = $grpo->U_Average2 ?? 0;
                                                                $totalLabMc += $qty * $moisture;
                                        
                                                                $mcDeduction = $moisture - $avg2;
                                                                $deductionPhp = ($mcDeduction / 100) * $price;
                                                            }
                                                        }
                                        
                                                        if ($totalArrivalWt) {
                                                            $delPrice = number_format(
                                                                ($price - max($deductionPhp, 0)) +
                                                                (($totalFreightPo + $totalTruckingPo) / $totalArrivalWt), 2
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
                                <table class="table table-bordered table-striped table-hover tablewithSearch">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>DEL PRICE</th>
                                            <th>BUYING PRICE</th>
                                            <th>Cottoni</th>
                                            <th></th>
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
                                            <td>
                                                {{ $month }}
                                            </td>
                                            <td class="text-center">{{ $avgDelivery }}</td> 
                                            <td class="text-center">{{ $avgBuying }}</td> 
                                            <td class="text-center">{{ $avgCottoni }}</td> 
                                            <td>{{ $buyingCottoni }}</td>
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
        
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Per Supplier </h5>
                </div>
                <div class="ibox-content">
                    <div class="wrapper wrapper-content animated fadeIn">
                        <div class="row">
                            <div style="max-width: 500px; width: 100%; margin: auto;">
                                <canvas id="supplierCottoniPie"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Per Area </h5>
                </div>
                <div class="ibox-content">
                    <div class="wrapper wrapper-content animated fadeIn">
                        <div class="row">
                            <div style="max-width: 500px; width: 100%; margin: auto;">
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

<script>
    const pieLabels = [];
    const pieData = [];

    const areaCottoni = {};

    @foreach ($suppliers as $supplier)
        @php
            $totalCottoni = 0;
            foreach ($months as $month) {
                $monthNum = date('m', strtotime($month));
                $grpos = $supplier->opdn->filter(function($grpo) use ($monthNum) {
                    $docDate = \Carbon\Carbon::parse($grpo->DocDate);
                    $firstLine = $grpo->grpoLines->first();
                    return $docDate->format('m') == $monthNum && optional($firstLine)->ItemCode === 'SWDSPIPHIL';
                });

                foreach ($grpos as $grpo) {
                    foreach ($grpo->grpoLines as $line) {
                        $totalCottoni += $line->Quantity;
                    }
                }
            }
        @endphp

        @if ($totalCottoni > 0)
            pieLabels.push("{{ $supplier->CardName }}");
            pieData.push({{ $totalCottoni }});

            @php
                $area = $supplier->OriginGroup;  
            @endphp
            if (!areaCottoni["{{ $area }}"]) {
                areaCottoni["{{ $area }}"] = 0;
            }
            areaCottoni["{{ $area }}"] += {{ $totalCottoni }};
        @endif
    @endforeach

    const pieColors = pieLabels.map(() =>
        `hsl(${Math.floor(Math.random() * 360)}, 70%, 60%)`
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
                    text: 'Total Cottoni per Supplier (Annual)'
                },
                datalabels: {
                    formatter: (value, context) => {
                        const label = context.chart.data.labels[context.dataIndex];
                        const data = context.chart.data.datasets[0].data;
                        const total = data.reduce((sum, val) => sum + val, 0);
                        const percentage = (value / total * 100).toFixed(1);
                        return `${label}\n${percentage}%`;
                    },
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    align: 'center',
                }
            }
        },
        plugins: [ChartDataLabels]
    };

    new Chart(document.getElementById('supplierCottoniPie'), pieConfig);

    const areaLabels = Object.keys(areaCottoni);
    const areaData = Object.values(areaCottoni);

    const areaColors = areaLabels.map(() =>
        `hsl(${Math.floor(Math.random() * 360)}, 70%, 60%)`
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
                    text: 'Total Cottoni per Area (Annual)'
                },
                datalabels: {
                    formatter: (value, context) => {
                        const label = context.chart.data.labels[context.dataIndex];
                        const data = context.chart.data.datasets[0].data;
                        const total = data.reduce((sum, val) => sum + val, 0);
                        const percentage = (value / total * 100).toFixed(1);
                        return `${label}\n${percentage}%`;
                    },
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    align: 'center',
                }
            }
        },
        plugins: [ChartDataLabels]
    };

    new Chart(document.getElementById('areaCottoniPie'), pieConfigByArea);
</script>
@endsection

