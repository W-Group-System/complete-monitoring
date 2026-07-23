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
                            <div class="col-md-2">
                                <label>Company</label>
                                <select class="chosen-select" name="company">
                                    <option value="All" {{ ($companyFilter ?? 'All') == 'All' ? 'selected' : '' }}>All</option>
                                    <option value="WHI" {{ $companyFilter == 'WHI' ? 'selected' : '' }}>WHI</option>
                                    <option value="CCC" {{ $companyFilter == 'CCC' ? 'selected' : '' }}>CCC</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label>Supplier</label>
                                <select class="chosen-select" name="supplier[]" multiple="multiple">
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->CardName }}"
                                            {{ in_array($supplier->CardName, (array) request('supplier', [])) ? 'selected' : '' }}>
                                            {{ $supplier->CardName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Start Date:</label>
                                <input type="date" name="start_date" value="{{ Request::get('start_date') }}" class="form-control" required>
                            </div>
                            <div class="col-md-2">
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
            <div class="ibox">
                <div class="ibox-content">
                    <button class="btn btn-success" onclick="exportTablesToExcel()">Export</button>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Summary SPINOSUM</h5>
                </div>
                <div class="ibox-content">
                    <div class="wrapper wrapper-content animated fadeIn">
                        <div class="row">
                            <div class="table-responsive">
                                <table id="table1" class="table table-bordered table-striped table-hover tablewithSearch">
                                    <thead>
                                        <tr>
                                            <th>PO#</th>
                                            <th>Reference No.</th>
                                            <th>PO Date</th>
                                            <th>Supplier Name</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalQuantity = 0;
                                            $totalPrice = 0;
                                            $totalAmount = 0;
                                        @endphp
                                        @foreach ($pos as $po)
                                        @php
                                            $totalQuantity += $po->Quantity;
                                            $totalPrice += $po->Price;
                                            $totalAmount += $po->Quantity * $po->Price;
                                        @endphp
                                            <tr>
                                                <td>{{ $po->DocNum }}</td>
                                                <td>{{ $po->NumAtCard }}</td>
                                                <td>{{ date('Y-m-d', strtotime($po->DocDate)) }}</td>
                                                <td>{{ $po->CardName }}</td> 
                                                <td>{{ number_format($po->Quantity,2) }}</td>
                                                <td>{{ number_format($po->Price,2) }}</td>
                                                <td>{{ number_format($po->Quantity * $po->Price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <td colspan="4"></td>
                                        <td>{{number_format($totalQuantity, 2)}}</td>
                                        <td>{{ number_format($totalQuantity ? $totalAmount / $totalQuantity : 0, 2) }}</td>
                                        <td>{{number_format($totalAmount, 2)}}</td>
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
                </div>
                <div class="ibox-content">
                    <div class="wrapper wrapper-content animated fadeIn">
                        <div class="row">
                            <div class="table-responsive">
                                @php
                                    $carQty = $pos->where('Company', 'CAR')->sum('Quantity');
                                    $carAmount = $pos->where('Company', 'CAR')->sum(function ($po) {
                                        return $po->Quantity * $po->Price;
                                    });

                                    $cccQty = $pos->where('Company', 'CCC')->sum('Quantity');
                                    $cccAmount = $pos->where('Company', 'CCC')->sum(function ($po) {
                                        return $po->Quantity * $po->Price;
                                    });

                                    $totalQty = $carQty + $cccQty;
                                    $totalAmount = $carAmount + $cccAmount;
                                @endphp
                                <table id="table2" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Company</th>
                                            <th>Quantity</th>
                                            <th>Average Price</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>CAR</td>
                                            <td>{{ number_format($carQty, 2) }}</td>
                                            <td>{{ number_format($carQty ? $carAmount / $carQty : 0, 2) }}</td>
                                            <td>{{ number_format($carAmount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>CCC</td>
                                            <td>{{ number_format($cccQty, 2) }}</td>
                                            <td>{{ number_format($cccQty ? $cccAmount / $cccQty : 0, 2) }}</td>
                                            <td>{{ number_format($cccAmount, 2) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th>{{ number_format($totalQty, 2) }}</th>
                                            <th>{{ number_format($totalQty ? $totalAmount / $totalQty : 0, 2) }}</th>
                                            <th>{{ number_format($totalAmount, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>    
                            </div> 
                        </div>
                    </div>   
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportTablesToExcel() {
        const wb = XLSX.utils.book_new();
    
        const table1 = document.getElementById('table1');
        const ws1 = XLSX.utils.table_to_sheet(table1);
        XLSX.utils.book_append_sheet(wb, ws1, 'Sheet1');
    
        const table2 = document.getElementById('table2');
        const ws2 = XLSX.utils.table_to_sheet(table2);
        XLSX.utils.book_append_sheet(wb, ws2, 'Sheet2');
    
        XLSX.writeFile(wb, 'report.xlsx');
    }
    </script> 
@endsection