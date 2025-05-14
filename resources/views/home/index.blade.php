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
                            <div class="col-md-5">
                                <label>Supplier</label>
                                <select class="chosen-select" name="supplier">
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->CardName }}" {{ $supplier->CardName == Request::get('supplier') ? 'selected' : '' }}>
                                            {{ $supplier->CardName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Start Date:</label>
                                <input type="date" name="start_date" value="{{ Request::get('start_date') }}" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>End Date:</label>
                                <input type="date" name="end_date" value="{{ Request::get('end_date') }}" class="form-control" required>
                            </div>
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
                    <h5>Complete Monitoring</h5>
                </div>
                <div class="ibox-content">
                    <div class="wrapper wrapper-content animated fadeIn">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover tablewithSearch">
                                    <thead>
                                        <tr>
                                            <th colspan="6" class="text-center" style="color: black; background-color: rgb(7, 133, 7);">PURCHASE ORDER</th>
                                            <th colspan="11" class="text-center" style="color: black; background-color: rgb(255, 255, 32);">ACTUAL DELIVERY</th>
                                            <th colspan="5" class="text-center" style="color: black; background-color: rgb(245, 105, 12);">DEDUCTIONS</th>
                                            <th colspan="5" class="text-center" style="color: black; background-color: rgb(37, 236, 243);">QUALITY RESULT</th>
                                            <th colspan="4" class="text-center" style="color: black; background-color: rgb(204, 37, 87);">PAYMENT</th>
                                        </tr>
                                        <tr>
                                            <th>PO# / GRPO</th>
                                            <th>PO DATE</th>
                                            <th>LOT Code</th>
                                            <th>SUPPLIER NAME</th>
                                            <th>ORIGIN</th>
                                            <th>SPECIE</th>
                                            <th>Container No.</th>
                                            <th>BL</th>
                                            <th>SHIPPING LINE</th>
                                            <th>PLATE #</th>
                                            <th>DESTINATION</th>
                                            <th>BL Date</th>
                                            <th>ARRIVAL DATE</th>
                                            <th>NO. OF BAGS</th>
                                            <th>ARRIVAL WT.</th>
                                            <th>PRICE</th>
                                            <th>AMOUNT</th>
                                            <th>MC Deduction</th>
                                            <th>MC Deduction/KG</th>
                                            <th>Truck Fee</th>
                                            <th>+ FREIGHT, TRUCK/KG</th>
                                            {{-- <th>Other Deduction</th> --}}
                                            {{-- <th>Trucking Cost</th> --}}
                                            {{-- <th>Freight Cost</th> --}}
                                            <th>Delivered Price</th>
                                            <th>OCCULAR MC</th>
                                            <th>LAB MC</th>
                                            <th>LAB YIELD</th>
                                            <th>KGS / CAGS</th>
                                            <th>VISCO</th>
                                            <th>DOWN PAYMENT</th>
                                            <th>DATE OF INITIAL</th>
                                            <th>FINAL PAYMENT</th>
                                            <th>DATE OF FINAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $SubtotalArrvalWeight = 0;
                                        @endphp
                                        @foreach ($grpos as $grpo)
                                        @php
                                            $NoOfBags = 0;
                                            $ArrivalWt = 0;
                                            $Amount = 0;
                                            $DownPayment = 0;
                                            $DownPaymentPercent = 0;
                                            $totalAmount = 0;
                                            $deduction = 0; 
                                            $FinalPayment = 0;
                                            $McDeduction = 0;
                                            $McDeductionKg = 0;
                                            $MDeduction = 0;
                                            $McDeductionPhp = 0;
                                            $MDeductionKg = 0;
                                            $totalFreightPo = 0;
                                            $totalTruckingPo = 0;
                                            $apDownPaymentLinesTotal = 0;
                                            $downpaymentDate = null;
                                        @endphp
                                            @foreach ($grpo->grpoLines as $line)
                                                @php
                                                    $NoOfBags += $line->U_Bagsperlot;
                                                    $ArrivalWt += $line->Quantity;
                                                    $Amount += ($line->Quantity * $line->Price);
                                                    $SubtotalArrvalWeight += $line->Quantity;
                                                @endphp
                                            @endforeach
                                            {{-- @foreach ($grpo->apDownPaymentLines as $dpLines)
                                                @php
                                                    $DownPayment += $dpLines->LineTotal;
                                                @endphp
                                            @endforeach --}}
                                            @foreach ($grpo->grpoLines as $grpoLine)
                                                @php
                                                    $poLine = $grpoLine->sourcePurchaseOrderLine;

                                                    if ($poLine) {
                                                        foreach ($poLine->downpaymentLines as $dpLine) {
                                                            $DownPayment += $dpLine->LineTotal;
                                                        }
                                                    }
                                                @endphp
                                            @endforeach
                                            @foreach ($grpo->apDownPayments as $downPayment)
                                                @php
                                                    $DownPaymentPercent = ($DownPayment * ($downPayment->DpmPrcnt/100) ?? 0);
                                                @endphp
                                            @endforeach
                                            @foreach ($grpo->apInvoices->unique('DocEntry') as $invoice)
                                                @php
                                                    $McDeduction =  (($Amount) * ( ($invoice->DiscPrcnt) / 100)) / $ArrivalWt;
                                                    $McDeductionKg = ($Amount) * ( ($invoice->DiscPrcnt) / 100);
                                                @endphp
                                            @endforeach
                                            @php
                                                $moist = optional($grpo->qualityResult)->U_MOIST ?? 0;
                                                $average2 = $grpo->U_Average2 ?? 0;

                                                $MDeduction = $moist - ($average2);
                                                $McDeductionPhp = ($MDeduction/100) * $grpo->grpoLines->first()->Price;
                                                $MDeductionKg = number_format($McDeductionPhp,2) * $ArrivalWt;
                                            @endphp
                                            @foreach($grpo->freightPoInvoice as $invoice)
                                            @foreach($invoice->DeductionLines as $freightLine)
                                                @php
                                                    $vat= ($freightLine->VatPrcnt/100);
                                                    $vatProduct = ($freightLine->LineTotal * $vat);
                                                    $totalFreightPo += ($freightLine->LineTotal + $vatProduct);
                                                @endphp
                                            @endforeach
                                            @endforeach
                                            @foreach($grpo->truckingPoInvoice as $truckInvoice)
                                            @foreach($truckInvoice->DeductionLines as $truckLine)
                                                @php
                                                    $vat= ($truckLine->VatPrcnt/100);
                                                    $vatProduct = ($truckLine->LineTotal * $vat);
                                                    $totalTruckingPo += ($truckLine->LineTotal + $vatProduct);
                                                @endphp
                                            @endforeach
                                            @endforeach
                                            <tr>
                                                <td>{{ $grpo->Combined_po_numbers }}</td> 
                                                @php
                                                    $poDates = explode(' / ', $grpo->PoDate); 
                                                    $formattedDates = collect($poDates)->map(function ($date) {
                                                        return \Carbon\Carbon::parse(trim($date))->format('M-d-Y');
                                                    })->implode(' / ');
                                                @endphp
                                                <td>{{ $formattedDates }}</td>
                                                <td>{{ $grpo->NumAtCard }}</td> 
                                                <td>{{ $grpo->CardName }}</td> 
                                                <td>
                                                    @if ($grpo->U_Origin )
                                                        {{ $grpo->U_Origin }}
                                                   @else     
                                                        {{ $grpo->U_Country }}
                                                    @endif
                                                </td>
                                                <td>{{ $grpo->grpoLines->first()->ItemCode }}</td>
                                                <td>{{ $grpo->U_ContainerNo }}</td>
                                                <td>{{ $grpo->U_BillLading }}</td>
                                                <td>{{ $grpo->U_Shhippingline }}</td>
                                                <td>{{ $grpo->U_Plateno }}</td>
                                                <td>{{ $grpo->grpoLines->first()->WhsCode }}</td>
                                                <td>{{ $grpo->ToWhsCode }}</td>
                                                <td>{{ \Carbon\Carbon::parse(trim($grpo->DocDate))->format('M-d-Y') }}</td>
                                                <td>{{ number_format($NoOfBags) }}</td>
                                                <td>{{ number_format($ArrivalWt,2) }}</td>
                                                <td>{{ number_format($grpo->grpoLines->first()->Price ,2)}}</td>
                                                <td>{{ number_format($Amount,2) }}</td>
                                                <td>
                                                    {{ $McDeductionPhp > 0 ? number_format($McDeductionPhp,2) : '-' }}
                                                </td>
                                                <td>
                                                    {{ $MDeductionKg > 0 ? number_format($MDeductionKg,2) : '-' }}

                                                </td>
                                                <td> 
                                                    @foreach ($grpo->apInvoices as $invoice)
                                                        @if ($invoice->apCreditNote())
                                                            {{ optional($invoice->apCreditNote())->DocTotal ? number_format(optional($invoice->apCreditNote())->DocTotal, 2) : '' }}
                                                        @endif
                                                    @endforeach 
                                                </td>
                                                <td>
                                                    {{ number_format(($totalFreightPo + $totalTruckingPo) / $ArrivalWt, 2) }}
                                                </td>
                                                <th>{{number_format(($grpo->grpoLines->first()->Price - max($McDeductionPhp, 0) ) + (($totalFreightPo + $totalTruckingPo) / $ArrivalWt),2)}}
                                                </th>
                                                <td>{{ optional($grpo->qualityResult)->U_OCULARMC }}</td>
                                                <td>{{ optional($grpo->qualityResult)->U_MOIST }}</td>
                                                <td>{{ optional($grpo->qualityResult)->U_LABYIELD }}</td>
                                                <td>
                                                    @if (optional($grpo->qualityResult)->U_POTGEL == "-")
                                                        {{ optional($grpo->qualityResult)->U_CALGEL }}
                                                    @else
                                                        {{ optional($grpo->qualityResult)->U_POTGEL }}
                                                    @endif
                                                </td>
                                                <td>{{ optional($grpo->qualityResult)->U_VISCO }}</td>
                                                <td>
                                                    @foreach($grpo->apInvoices->unique('DocEntry') as $invoice)
                                                        @foreach($invoice->pch9 as $downPayment) 
                                                        @php
                                                            $downpaymentDate = $downPayment->BsDocDate
                                                        @endphp
                                                        {{ number_format($downPayment->DrawnSum,2) }}
                                                        {{-- {{ $downPayment }} --}}
                                                        @endforeach
                                                    @endforeach
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($downpaymentDate)->format('Y-m-d') }}

                                                </td>
                                                @foreach($grpo->apInvoices->unique('DocEntry') as $invoice)
                                                    @foreach($invoice->paymentMappings as $paymentMapping) 
                                                    @php
                                                        if ($paymentMapping->InvType == 19) {
                                                            $deduction += $paymentMapping->SumApplied;
                                                        } else {
                                                            $totalAmount += $paymentMapping->SumApplied;
                                                        }
                                                    @endphp
                                                    @endforeach
                                                @endforeach
                                                @php
                                                    $FinalPayment = $totalAmount - $deduction;
                                                @endphp
                                                <td>
                                                    {{ number_format($FinalPayment,2) }}
                                                </td>
                                                <td>
                                                    @foreach($grpo->apInvoices->unique('DocEntry') as $invoice)
                                                        @foreach($invoice->payments as $outgoing) 
                                                        {{ \Carbon\Carbon::parse($outgoing->DocDate)->format('Y-m-d') }}
                                                        @endforeach
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot style="background-color:rgb(18, 126, 197); color: black;">
                                        <td colspan="14"></td>
                                        <td>{{number_format($SubtotalArrvalWeight, 2)}}</td>
                                        <td colspan="14"></td>
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
                </div>
                <div class="ibox-content">
                    <div class="wrapper wrapper-content animated fadeIn">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover tablewithSearch">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="color: black; background-color: rgb(58, 164, 235);">{{optional($grpos->first())->CardName}}</th>
                                            <th>Delivered</th>
                                            <th>Buying</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="text-center" style="color: black; background-color: rgb(58, 164, 235);">{{optional($grpos->first())->CardName}}</th>
                                            <th>Delivered</th>
                                            <th>Buying</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th>SWD Price/Powder Yield</th>
                                            <th>PRICE</th>
                                            <th>PRICE</th>
                                            <th>MONTH</th>
                                            <th>TOTAL COTTONII</th>
                                            <th>MC AT DESTINATION</th>
                                            <th></th>
                                            <th></th>
                                            <th>SWD Price/Powder Yield</th>
                                            <th>PRICE</th>
                                            <th>PRICE</th>
                                            <th>MONTH</th>
                                            <th>TOTAL SPINOSUM</th>
                                            <th>MC AT DESTINATION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            use Carbon\Carbon;
                                    
                                            $firstGRPO_COTPHIL = $grpos->filter(fn($grpo) => optional($grpo->grpoLines->first())->ItemCode === 'SWDCOTPHIL')->first();
                                            $firstGRPO_SPIPHIL = $grpos->filter(fn($grpo) => optional($grpo->grpoLines->first())->ItemCode === 'SWDSPIPHIL')->first();
                                    
                                            $year = $firstGRPO_COTPHIL ? Carbon::parse(explode(' / ', $firstGRPO_COTPHIL->DocDate)[0])->year : now()->year;
                                    
                                            $allMonths = collect(range(1, 12))->mapWithKeys(fn($m) => [
                                                Carbon::create($year, $m, 1)->format('Y-m') => null
                                            ]);
                                    
                                            $monthlyData_COTPHIL = $grpos->filter(fn($grpo) => optional($grpo->grpoLines->first())->ItemCode === 'SWDCOTPHIL')
                                                ->groupBy(fn($grpo) => Carbon::parse(explode(' / ', $grpo->DocDate)[0])->format('Y-m'));
                                    
                                            $monthlyData_SPIPHIL = $grpos->filter(fn($grpo) => optional($grpo->grpoLines->first())->ItemCode === 'SWDSPIPHIL')
                                                ->groupBy(fn($grpo) => Carbon::parse(explode(' / ', $grpo->DocDate)[0])->format('Y-m'));
                                    
                                            $monthlyData_COTPHIL = $allMonths->merge($monthlyData_COTPHIL);
                                            $monthlyData_SPIPHIL = $allMonths->merge($monthlyData_SPIPHIL);
                                        @endphp
                                    
                                        @foreach ($allMonths as $month => $value)
                                            @php
                                                $grpos_COTPHIL = $monthlyData_COTPHIL[$month] ?? null;
                                                $totalArrivalWt_COTPHIL = 0;
                                                $totalWeightedAmount_COTPHIL = 0;
                                                $totalLabMc_COTPHIL = 0;

                                                $totalCottFreightPo = 0;
                                                $totalCottTruckingPo = 0;
                                                $McCottDeductionPhp = 0;
                                                $weightedAvgDeliveredPrice_COTPHIL =0;
                                    
                                                if ($grpos_COTPHIL) {
                                                    foreach ($grpos_COTPHIL as $grpo) {
                                                        
                                                        foreach($grpo->freightPoInvoice as $invoice){
                                                            foreach($invoice->DeductionLines as $freightLine) {
                                                                $vat= ($freightLine->VatPrcnt/100);
                                                                $vatProduct = ($freightLine->LineTotal * $vat);
                                                                $totalCottFreightPo += ($freightLine->LineTotal + $vatProduct);
                                                            }
                                                        }
                                                        foreach($grpo->truckingPoInvoice as $truckInvoice) {
                                                            foreach($truckInvoice->DeductionLines as $truckLine) {
                                                                $vat= ($truckLine->VatPrcnt/100);
                                                                $vatProduct = ($truckLine->LineTotal * $vat);
                                                                $totalCottTruckingPo += ($truckLine->LineTotal + $vatProduct);
                                                            }
                                                        }
                                                        foreach ($grpo->grpoLines as $line) {
                                                            $totalArrivalWt_COTPHIL += $line->Quantity;
                                                            $totalWeightedAmount_COTPHIL += ($line->Quantity * $line->Price);
                                                            $moistureContent = (float) optional($grpo->qualityResult)->U_MOIST;
                                                            $totalLabMc_COTPHIL += (float) $line->Quantity * $moistureContent;

                                                            $MCottDeduction = optional($grpo->qualityResult)->U_MOIST - ($grpo->U_Average2 ?? 0);
                                                            $McCottDeductionPhp = ($MCottDeduction/100) * $line->Price;
                                                            $weightedAvgDeliveredPrice_COTPHIL = $totalArrivalWt_COTPHIL ? number_format(($line->Price - max($McCottDeductionPhp, 0) ) + (($totalCottFreightPo + $totalCottTruckingPo) / $totalArrivalWt_COTPHIL),2): null;

                                                        }
                                                    }
                                                }
                                    
                                                $weightedAvgPrice_COTPHIL = $totalArrivalWt_COTPHIL ? $totalWeightedAmount_COTPHIL / $totalArrivalWt_COTPHIL : null;
                                                $weightedMc_COTPHIL = $totalArrivalWt_COTPHIL ? $totalLabMc_COTPHIL / $totalArrivalWt_COTPHIL : null;
                

                                                $grpos_SPIPHIL = $monthlyData_SPIPHIL[$month] ?? null;
                                                $totalArrivalWt_SPIPHIL = 0;
                                                $totalWeightedAmount_SPIPHIL = 0;
                                                $totalLabMc_SPIPHIL = 0;

                                                $totalSpiFreightPo = 0;
                                                $totalSpiTruckingPo = 0;
                                                $McSpiDeductionPhp = 0;
                                                $weightedAvgDeliveredPrice_SPIPHIL =0;
                                    
                                                if ($grpos_SPIPHIL) {
                                                    foreach ($grpos_SPIPHIL as $grpo) {

                                                        foreach($grpo->freightPoInvoice as $invoice){
                                                            foreach($invoice->DeductionLines as $freightLine) {
                                                                $vat= ($freightLine->VatPrcnt/100);
                                                                $vatProduct = ($freightLine->LineTotal * $vat);
                                                                $totalSpiFreightPo += ($freightLine->LineTotal + $vatProduct);
                                                            }
                                                        }
                                                        foreach($grpo->truckingPoInvoice as $truckInvoice) {
                                                            foreach($truckInvoice->DeductionLines as $truckLine) {
                                                                $vat= ($truckLine->VatPrcnt/100);
                                                                $vatProduct = ($truckLine->LineTotal * $vat);
                                                                $totalSpiTruckingPo += ($truckLine->LineTotal + $vatProduct);
                                                            }
                                                        }
                                                        foreach ($grpo->grpoLines as $line) {
                                                            $totalArrivalWt_SPIPHIL += $line->Quantity;
                                                            $totalWeightedAmount_SPIPHIL += ($line->Quantity * $line->Price);
                                                            $moistureContent = (float) optional($grpo->qualityResult)->U_MOIST;
                                                            $totalLabMc_SPIPHIL += (float) $line->Quantity * $moistureContent;

                                                            $MSpiDeduction = optional($grpo->qualityResult)->U_MOIST - ($grpo->U_Average2 ?? 0);
                                                            $McSpiDeductionPhp = ($MSpiDeduction/100) * $line->Price;
                                                            $weightedAvgDeliveredPrice_SPIPHIL = $totalArrivalWt_SPIPHIL ? number_format(($line->Price - max($McSpiDeductionPhp, 0) ) + (($totalSpiFreightPo + $totalSpiTruckingPo) / $totalArrivalWt_SPIPHIL),2): null;
                                                        }
                                                    }
                                                }
                                    
                                                $weightedAvgPrice_SPIPHIL = $totalArrivalWt_SPIPHIL ? $totalWeightedAmount_SPIPHIL / $totalArrivalWt_SPIPHIL : null;
                                                $weightedMc_SPIPHIL = $totalArrivalWt_SPIPHIL ? $totalLabMc_SPIPHIL / $totalArrivalWt_SPIPHIL : null;
                                            @endphp
                                    
                                            <tr>
                                                {{-- SWDCOTPHIL Data --}}
                                                <td></td>
                                                <td>{{$weightedAvgDeliveredPrice_COTPHIL}}</td>
                                                <td>{{ $weightedAvgPrice_COTPHIL !== null ? number_format($weightedAvgPrice_COTPHIL, 2) : '' }}</td>  
                                                <td>{{ Carbon::parse($month . '-01')->format('F Y') }}</td> 
                                                <td>{{ $totalArrivalWt_COTPHIL > 0 ? number_format($totalArrivalWt_COTPHIL, 2) : '' }}</td>
                                                <td>{{ number_format($weightedMc_COTPHIL, 2) }}</td>
                                                <td></td>
                                                <td></td>
                                    
                                                <td></td>
                                                <td>{{$weightedAvgDeliveredPrice_SPIPHIL}}</td>
                                                <td>{{ $weightedAvgPrice_SPIPHIL !== null ? number_format($weightedAvgPrice_SPIPHIL, 2) : '' }}</td>
                                                <td>{{ Carbon::parse($month . '-01')->format('F Y') }}</td> 
                                                <td>{{ $totalArrivalWt_SPIPHIL > 0 ? number_format($totalArrivalWt_SPIPHIL, 2) : '' }}</td>
                                                <td>{{ number_format($weightedMc_SPIPHIL, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>                                    
                                </table>
                            </div> 
                        </div>
                    </div>   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection