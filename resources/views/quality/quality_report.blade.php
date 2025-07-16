@extends('layouts.header')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="card-body">
                    <h4 class="ibox-title">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-md-3" style="margin-top: 22px">
                                    <form method="GET" action="{{ url('/qualityReport') }}" class="form-inline" style="margin-bottom: 15px;" onsubmit="show()">
                                        <div class="form-group">
                                            <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </form>
                                </div>
                                <form method='GET' onsubmit='show();' enctype="multipart/form-data" >
                                 @csrf
                                    <div class="col-md-3">
                                        <label>Start Date:</label>
                                        <input type="date" name="start_date" value="{{ Request::get('start_date') }}" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label>End Date:</label>
                                        <input type="date" name="end_date" value="{{ Request::get('end_date') }}" class="form-control">
                                    </div>
                                    <div class="col-md-1" style="margin-top: 22px">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                    <div class="col-md-1" style="margin-top: 22px">
                                        <button class="btn btn-success" onclick="exportTablesToExcel()">Export</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </h4>
                    <div class="ibox-content">
                        <div class="wrapper wrapper-content animated fadeIn">
                            <div class="row">
                                <div class="table-responsive" >
                                    <table class="table table-striped table-bordered table-hover tablewithSearch" id="table">
                                        <thead>
                                             <tr>
                                                <th>RecDate</th>
                                                <th>Source</th>
                                                <th>CardName</th>
                                                <th>SW_CODE</th>
                                                <th>DRNo</th>
                                                <th>Container</th>
                                                <th>Bin</th>
                                                <th>Quantity</th>
                                                <th>NoofBag</th>
                                                <th>Variety</th>
                                                <th>A</th>
                                                <th>O</th>
                                                <th>L</th>
                                                <th>CAW</th>
                                                <th>CAW RATIO</th>
                                                <th>LabYield</th>
                                                <th>SandSaltNMT</th>
                                                <th>Salt</th>
                                                <th>Ph</th>
                                                <th>Viscosity</th>
                                                <th>KGS</th>
                                                <th>WGS</th>
                                                <th>CaGS</th>
                                                <th>NaCL</th>
                                                <th>Remarks</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($grpos as $grpo)
                                            @php           
                                                $ArrivalWt = 0;
                                                $NoOfBags = 0;
                                            @endphp
                                            @foreach ($grpo->grpoLines as $line)
                                                @php
                                                    $ArrivalWt += $line->Quantity;
                                                    $NoOfBags += $line->U_Bagsperlot;
                                                @endphp
                                            @endforeach
                                            <tr> 
                                                <td>{{ \Carbon\Carbon::parse($grpo->DocDate)->format('Y-m-d') }}</td>
                                                <td>{{ !empty($grpo->U_Origin) ? $grpo->U_Origin : $grpo->U_Country }}</td>
                                                <td>{{ $grpo->CardName }}</td>
                                                <td>{{ $grpo->NumAtCard }}</td>
                                                <td>{{ optional($grpo->quality_created)->dr_rr }}</td>
                                                <td>{{ $grpo->U_ContainerNo }}</td>
                                                <td>{{ optional($grpo->quality_created)->location_bin }}</td>
                                                <td>{{ number_format($ArrivalWt, 2, '.', ',') }}</td>
                                                <td>{{ $NoOfBags }}</td>
                                                <td>
                                                    @if (optional($grpo->quality_created)->seaweeds == 'Eucheuma Spinosum')
                                                        E. Spinosum
                                                    @else
                                                        E. Cottonii
                                                    @endif
                                                </td>
                                                @php
                                                    $chemicalTestings = json_decode(optional($grpo->quality_created)->chemical_testings, true);
                                                    $RecoveryLabYield = null;
                                                    $Moisture = null;
                                                    $Caw = null;
                                                    $CawRatio = null;
                                                    $phSpi = null;
                                                    $phCot = null;
                                                    $ViscosityCot = null;
                                                    $ViscositySpi = null;
                                                    $Kgs = null;
                                                    $Wgs = null;
                                                    $Cgs = null;
                                                    $NaCl = null;

                                                    if (is_array($chemicalTestings)) {
                                                        foreach ($chemicalTestings as $test) {
                                                            if (isset($test['parameter']) && strpos($test['parameter'], '% Recovery (lab yield)') !== false) {
                                                                $RecoveryLabYield = $test['result'];
                                                            }
                                                            if (isset($test['parameter']) && trim($test['parameter']) === '4. CAW') {
                                                                $Caw = $test['result'];
                                                            }
                                                            if (isset($test['parameter']) && trim($test['parameter']) === '5. CAW Ratio') {
                                                                $CawRatio = $test['result'];
                                                            }
                                                            if (isset($test['parameter']) && trim($test['parameter']) === '1. % Moisture (weeds)') {
                                                                $Moisture = $test['result'];
                                                            }
                                                            if (isset($test['specification']) && trim($test['specification']) === 'E. cottonii:8 - 11 @1.5% at 60°C') {
                                                                $phCot = $test['result'];
                                                            }
                                                            if (isset($test['specification']) && trim($test['specification']) === 'E. spinosum: 7.5 - 9.5 @ 2.0%, 60°C') {
                                                                $phSpi = $test['result'];
                                                            }
                                                            if (isset($test['specification']) && trim($test['specification']) === 'E. Cottonii - Min of 20cps @ 1.5%, 75°C') {
                                                                $ViscosityCot = $test['result'];
                                                            }
                                                            if (isset($test['specification']) && trim($test['specification']) === 'E. spinosum: Minimum of 20 cps @ 2.0% w/w at 75°C') {
                                                                $ViscositySpi = $test['result'];
                                                            }
                                                            if (isset($test['parameter']) && trim($test['parameter']) === '10. Potassium Gel Strength') {
                                                                $Kgs = $test['result'];
                                                            }
                                                            if (isset($test['parameter']) && trim($test['parameter']) === '9. Water Gel Strength') {
                                                                $Wgs = $test['result'];
                                                            }
                                                            if (isset($test['parameter']) && trim($test['parameter']) === '11.  Calcium Gel Strength') {
                                                                $Cgs = $test['result'];
                                                            }
                                                            if (isset($test['parameter']) && trim($test['parameter']) === '6. % Salt (NaCl)') {
                                                                $NaCl = $test['result'];
                                                            }
                                                        }
                                                    }
                                                @endphp

                                                <td>{{!empty( optional($grpo->quality_created)->agreed_mc) ?  optional($grpo->quality_created)->agreed_mc . '%' : '' }}</td>
                                                <td>{{ !empty(optional($grpo->quality_created)->ocular_mc) ? optional($grpo->quality_created)->ocular_mc . "%" : '' }}</td>
                                                <td>{{ !empty($Moisture) ? $Moisture . "%" : '' }}</td>
                                                <td>{{ $Caw ?? '' }}</td>
                                                <td>{{ $CawRatio ?? '' }}</td>
                                                <td>{{ $RecoveryLabYield ?? '' }}</td>
                                                <td>{{ optional(optional($grpo->quality_created)->sand)->impurities }}</td>
                                                <td>{{ optional(optional($grpo->quality_created)->sand)->percent }}</td>
                                                <td>
                                                    @if (optional($grpo->quality_created)->seaweeds == 'Eucheuma Spinosum')
                                                        {{ $phSpi }}
                                                    @else
                                                        {{ $phCot }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (optional($grpo->quality_created)->seaweeds == 'Eucheuma Spinosum')
                                                        {{ $ViscositySpi }}
                                                    @else
                                                        {{ $ViscosityCot }}
                                                    @endif
                                                </td>
                                                <td>{{ number_format($Kgs) ?? '' }}</td>
                                                <td>{{ number_format($Wgs) ?? '' }}</td>
                                                <td>{{ $Cgs ?? '' }}</td>
                                                <td>{{ $NaCl ?? '' }}</td>
                                                <td>{{ optional($grpo->quality_created)->remarks ?? '' }}</td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {!! $grpos->appends(['search' => $search])->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportTablesToExcel() {
        const wb = XLSX.utils.book_new();
    
        const table = document.getElementById('table');
        const ws1 = XLSX.utils.table_to_sheet(table);
        XLSX.utils.book_append_sheet(wb, ws1, 'Sheet1');

        XLSX.writeFile(wb, 'report.xlsx');
    }
</script>
@endsection
