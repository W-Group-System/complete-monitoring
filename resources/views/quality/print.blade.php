
    <style>
    body {
        font-family: sans-serif;
        margin: 0;
        padding: 0;
    }
    .header-container {
        text-align: center;
        width: 100%;
        margin-bottom: 10px;
    }
    .img {
        width: 90px;
        height: auto;
    }
    .details table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    .details td, th {
        /* border: 1px solid #000; */
        vertical-align: top;
    }
    .label{
        font-weight: bold;
    }
    .label-right{
        font-weight: bold;
        padding-left: 10px !important;
        width: 14% !important;
    }
    .label-right-two{
        font-weight: bold;
        padding-left: 10px !important;
        width: 15% !important;
    }
    .underline-two {
        border-bottom:solid 1px #000 !important;
        width: 23% !important;   
    }
    .underline {
        border-bottom:solid 1px #000 !important;
        width: 24% !important;   
    }
    .top-table td{
        border: solid 1px #000 !important;
        padding: 10px !important;
    }
    .border-frame{
        border: solid 1px #000 !important;
        padding: 10px !important;
        margin-top: 15px !important;
    }
    .inspection-table{
        margin-top:15px;
    }
     .inspection-table table{
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .inspection-table td{
        border: solid 1px #000 !important;
    }

    .inspection-table th.section {
        background-color: #ccc;
        border: solid 1px #000 !important;
        text-align: left;
    }
    .inspection-table .section_two th{
        background-color: #f1efef;
        border: solid 1px #000 !important;
    }

    .checkbox-cell {
        width: 12px;
        height: 12px;
        margin-right: 5px;
        vertical-align: middle;
    }
    .checkbox-label {
        display: inline-block;
        margin-right: 15px;
    }
    .checkbox-label-two {
        display: inline-block;
        margin-right: 10px;
    }
    .seaweeds-row {
        white-space: nowrap;
    }

    .seaweeds-label {
        align-items: center;
        font-size: 12px;
    }
    .seaweeds {
        width: 10px;
        height: 10px;
        margin-right: 5px;
        vertical-align: middle;
    }
    .nowrap {
        white-space: nowrap;
    }
    .center{
        text-align: center !important;
        vertical-align: middle !important;
    }
    .remarks-line {
        width: 100%;
        border-bottom: 1px solid #000;
        font-size: 12px;
        line-height: 1.4em;
        height: 1.4em;
        white-space: nowrap;
        overflow: hidden;
    }
    .signature {
        text-align: center;
    }
    .signature-left,
    .signature-right {
        width: 40%;
        text-align: center;
        font-size: 12px;
        display: inline-block;
        vertical-align: top;
    }
    .signature-line {
        display: block;
        border-bottom: 1px solid #000;
        width: 100%;
        margin: 5px 0;
    }

    .signature-left {
        left: 10%;
    }

    .signature-right {
        right: 10%;
    }

    .signature-line {
        display: block;
        border-bottom: solid 1px #000;
        width: 70%;
        margin: 5px auto;
        height: 10px;
    }
    .signature-label {
        font-weight: bold;
        display: block;
        text-align: left;
        width: 70%;
        margin: 10px auto;
    }



</style>

<div class="header-container">
    <img class="img" src="{{ asset('/img/whi_logo.png')}}" alt="Company Logo">
    <div><strong>SEAWEEDS DELIVERY AND INSPECTION REPORT</strong></div>
</div>

<body>
    <div class="details">
        <div class="top-table">
            <table>
                <tr>
                    <td style="width: 70%;">
                        <strong>SW Code:</strong> {{ $details->NumAtCard ?? '' }}
                    </td>
                    <td style="width: 30%;">
                        <strong>DR/RR No:</strong> {{ optional($details->quality_created)->dr_rr }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="border-frame">
            <table>
                <tr>
                    <td style="width: 11%;"><span class="label">Date of Delivery:</span></td>
                    <td class="underline">{{ \Carbon\Carbon::parse($details->DocDate)->format('F j,Y') }}</td>
                    <td class="label-right"><span class="label">Container/Plate No:</span></td>
                    <td class="underline">{{ $details->U_ContainerNo }}</td>
                </tr>
                <tr>
                    <td><span class="label">Supplier:</span></td>
                    <td class="underline">{{ $details->CardName }}</td>
                    <td class="label-right"><span class="label">Source:</span></td>
                    <td class="underline">{{ !empty($details->U_Origin) ? $details->U_Origin : $details->U_Country }}</td>
                </tr>
                @php           
                    $ArrivalWt = 0;
                    $NoOfBags = 0;
                @endphp
                @foreach ($details->grpoLines as $line)
                    @php
                        $ArrivalWt += $line->Quantity;
                        $NoOfBags += $line->U_Bagsperlot;
                    @endphp
                @endforeach
                <tr>
                    <td><span class="label">QTY Delivered:</span></td>
                    <td class="underline">{{ number_format($ArrivalWt, 2, '.', ',') }}</td>
                    <td class="label-right"><span class="label">Number of Bags:</span></td>
                    <td class="underline">{{ $NoOfBags }}</td>
                </tr>
                <tr>
                    <td><span class="label">Location/ BIN:</span></td>
                    <td class="underline">{{ optional($details->quality_created)->location_bin }}</td>
                    <td></td>
                    <td class="underline"></td>
                </tr>
            </table>
        </div>

        <div class="border-frame">
            <table>
                <tr>
                    <td style="width: 7%;"><span class="label">Seaweeds:</span></td>
                    {{-- <td>
                        <label class="seaweeds-label">
                            <input type="checkbox" class="seaweeds"
                                {{ optional($details->quality_created)->seaweeds == 'Eucheuma Cottonii' ? 'checked' : '' }}>
                            Eucheuma Cottonii
                        </label>
                        <label class="seaweeds-label">
                            <input type="checkbox" class="seaweeds"
                                {{ optional($details->quality_created)->seaweeds == 'Eucheuma Spinosum' ? 'checked' : '' }}>
                            Eucheuma Spinosum
                        </label>
                    </td> --}}
                    <td>
                        <div class="seaweeds-row">
                            <label class="seaweeds-label">
                                <input type="checkbox" class="seaweeds"
                                    {{ optional($details->quality_created)->seaweeds == 'Eucheuma Cottonii' ? 'checked' : '' }}>
                                Eucheuma Cottonii
                            </label>
                            <label class="seaweeds-label">
                                <input type="checkbox" class="seaweeds"
                                    {{ optional($details->quality_created)->seaweeds == 'Eucheuma Spinosum' ? 'checked' : '' }}>
                                Eucheuma Spinosum
                            </label>
                        </div>
                    </td>
                    <td class="label-right-two"><span class="label">Haghag:</span></td>
                    <td class="">
                        <label class="checkbox-label">
                            <input type="checkbox" class="checkbox-cell"
                                {{ optional($details->quality_created)->haghag == '10%' ? 'checked' : '' }}>
                            10%
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" class="checkbox-cell"
                                {{ optional($details->quality_created)->haghag == '100%' ? 'checked' : '' }}>
                            100%
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" class="checkbox-cell"
                                {{ optional($details->quality_created)->haghag == 'N/A%' ? 'checked' : '' }}>
                            N/A
                        </label>
                    </td>
                </tr>
                <tr>
                    <td><span class="label">Ocular MC:</span></td>
                    <td class="underline">{{ optional($details->quality_created)->ocular_mc }}</td>
                    <td class="label-right-two"><span class="label">Agreed MC:</span></td>
                    <td class="underline-two">{{ optional($details->quality_created)->agreed_mc }}%</td>
                </tr>
                <tr>
                    <td><span class="label"></span></td>
                    <td class="underline"></td>
                    <td class="label-right-two"><span class="label">Agreed Budget Yield:</span></td>
                    <td class="underline-two">{{ optional($details->quality_created)->budget_yield }}%</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="inspection-table">
        <table>
            <tr><th colspan="4" class="section">I. PHYSICAL AND OCULAR INSPECTION</th></tr>
            <tr class="section_two">
                <th style="width: 20%">Parameters</th>
                <th style="width: 65%">Condition</th>
                <th colspan="2" style="width: 15%">Remarks</th>
            </tr>
            <tr>
                <td>1. Color of Seaweeds</td>
            @php
                    $storedConditions = json_decode($details->quality_created->colors->condition ?? '[]', true);
                    $storedAppearance = json_decode($details->quality_created->appearance->condition ?? '[]', true);

                @endphp

                <td>
                    @foreach (['Purple', 'Brown', 'Black', 'Pink', 'Green', 'Yellow'] as $color)
                        <label class="checkbox-label-two">
                            <input 
                                type="checkbox" 
                                class="checkbox-cell" 
                                name="condition[]" 
                                id="{{ strtolower($color) }}" 
                                value="{{ $color }}"
                                {{ in_array($color, $storedConditions) ? 'checked' : '' }}>
                            {{ $color }}
                        </label>
                    @endforeach
                </td>
                <td colspan="2">{{ optional(optional($details->quality_created)->colors)->remarks }}</td>
            </tr>
            <tr>
                <td>2. Appearance</td>
                <td>
                    @foreach (['Thick', 'Moderately Thick', 'Thin', 'Small', 'Medium', 'Giant'] as $appearance)
                        <label class="checkbox-label-two">
                            <input 
                                type="checkbox" 
                                class="checkbox-cell" 
                                name="condition[]" 
                                id="{{ strtolower($appearance) }}" 
                                value="{{ $appearance }}"
                                {{ in_array($appearance, $storedAppearance) ? 'checked' : '' }}>
                            {{ $appearance }}
                        </label>
                    @endforeach
                </td>
                <td colspan="2">{{ optional(optional($details->quality_created)->appearance)->remarks }}</td>
            </tr>
            <tr>
                <td>3. Ice-ice</td>
                <td>
                    <label class="checkbox-label-two">
                        <input type="checkbox" class="checkbox-cell"
                            {{ optional($details->quality_created)->ice == 'Present' ? 'checked' : '' }}>
                        Present
                    </label>
                    <label class="checkbox-label-two">
                        <input type="checkbox" class="checkbox-cell"
                            {{ optional($details->quality_created)->ice == 'Absent' ? 'checked' : '' }}>
                        Absent
                    </label>
                </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>4. Moss</td>
                <td>
                    <label class="checkbox-label-two">
                        <input type="checkbox" class="checkbox-cell"
                            {{ optional($details->quality_created)->moss == 'Present' ? 'checked' : '' }}>
                        Present
                    </label>
                    <label class="checkbox-label-two">
                        <input type="checkbox" class="checkbox-cell"
                            {{ optional($details->quality_created)->moss == 'Absent' ? 'checked' : '' }}>
                        Absent
                    </label>
                </td>
                <td colspan="2"></td>
            </tr>
        </table>
        <table>
            <tr><th colspan="4" class="section">II. PHYSICO-CHEMICAL TESTING</th></tr>
            <tr class="section_two">
                <th style="width: 20%">Parameters</th>
                <th style="width: 50%">Specifications</th>
                <th style="width: 15%">Results</th>
                <th style="width: 15%">Remarks</th>
            </tr>
            @php
                $qualityItems = [
                    ['parameter' => '1. % Moisture (weeds)', 'spec' => 'Based on agreed MC'],
                    ['parameter' => '2. % Recovery (lab yield)', 'spec' => 'Based on Agreed Budget Yield'],
                    ['parameter' => '3. % Impurities', 'spec' => 'Maximum of 2.0%'],
                    ['parameter' => '4. CAW', 'spec' => 'Minimum 32%'],
                    ['parameter' => '5. CAW Ratio', 'spec' => 'Minimum 1.3'],
                    ['parameter' => '6. % Salt (NaCl)', 'spec' => 'Maximum of 20%'],
                    ['parameter' => '7. Viscosity', 'spec' => 'E. Cottonii - Min of 20cps @ 1.5%, 75°C'],
                    ['parameter' => '', 'spec' => 'E. spinosum: Minimum of 20 cps @ 2.0% w/w at 75°C'],
                    ['parameter' => '8. pH', 'spec' => 'E. cottonii:8 - 11 @1.5% at 60°C'],
                    ['parameter' => '', 'spec' => 'E. spinosum: 7.5 - 9.5 @ 2.0%, 60°C'],
                    ['parameter' => '9. Water Gel Strength', 'spec' => 'E. Cottonii - Min of 300g/cm² @ 1.5% w/w 20°C'],
                    ['parameter' => '10. Potassium Gel Strength', 'spec' => 'E. cottonii:Minimum of 800 g/cm2; @ 1.5% 0.2% KCl at 20°C'],
                    ['parameter' => '11.  Calcium Gel Strength', 'spec' => 'E. Spinosum - 20-80 g/cm² @ 2% 0.2% CaCI at 20°C'],
                ];
            @endphp
            @php
                $testingMap = collect(optional($details->quality_created)->chemical_testings)
                    ->mapWithKeys(function ($item) {
                        $key = trim($item->parameter ?? '') . '|' . trim($item->specification ?? '');
                        return [$key => $item];
                    });
            @endphp
            @foreach ($qualityItems as $item)
                @php
                    $param = trim($item['parameter']);
                    $spec = trim($item['spec']);
                    $key = $param . '|' . $spec;
                    $stored = $testingMap[$key] ?? null;
                @endphp
            <tr>
                <td>{{ $param }}</td>
                <td>{{ $spec }} </td>
                <td>
                    @if(str_contains($param, '%') && isset($stored->result))
                        {{ $stored->result }}%
                    @elseif(str_contains($param, 'Gel Strength') && isset($stored->result))
                        {{ (int) $stored->result }}
                    @else
                        {{ $stored->result ?? '' }}
                    @endif
                </td>
                <td>{{ $stored->remarks ?? '' }}</td>
            </tr>
            @endforeach
        </table>
        <table>
            <tr><th colspan="5" class="section">III. FOREIGN MATERIAL REPORT </th></tr>
            <tr class="section_two">
                <th style="width:25%">Foreign Matter (in kg)</th>
                <th style="width: 17%">Impurities</th>
                <th style="width: 20%">Weight of Sample</th>
                <th style="width: 20%">Percent Impurities</th>
                <th style="width: 18%">Parts per million (max 100 ppm)</th>
            </tr>
            <tr>
                <td>
                    <div>1. Tie-ties/ FOM</div>
                    <label class="checkbox-label-two">
                        <input type="checkbox" class="checkbox-cell"
                            {{ optional(optional($details->quality_created)->tie_tie)->foreign_matter == 'Present' ? 'checked' : '' }}>
                        Present
                    </label>
                    <label class="checkbox-label-two">
                        <input type="checkbox" class="checkbox-cell"
                            {{ optional(optional($details->quality_created)->tie_tie)->foreign_matter == 'Absent' ? 'checked' : '' }}>
                        Present
                    </label>
                </td>
                <td class="center">{{ optional(optional($details->quality_created)->tie_tie)->impurities}}</td>
                <td class="center">{{ optional(optional($details->quality_created)->tie_tie)->weight}}</td>
                <td class="center">{{ optional(optional($details->quality_created)->tie_tie)->percent}}</td>
                <td class="center">{{ number_format(optional(optional($details->quality_created)->tie_tie)->parts_million)}}</td>
            </tr>
            <tr>
                <td>
                    <div>1. Sand/ Salt</div>
                    <label class="checkbox-label-two">
                        <input type="checkbox" class="checkbox-cell"
                            {{ optional(optional($details->quality_created)->sand)->foreign_matter == 'Present' ? 'checked' : '' }}>
                        Present
                    </label>
                    <label class="checkbox-label-two">
                        <input type="checkbox" class="checkbox-cell"
                            {{ optional(optional($details->quality_created)->sand)->foreign_matter == 'Absent' ? 'checked' : '' }}>
                        Present
                    </label>
                </td>
                <td class="center">{{ optional(optional($details->quality_created)->sand)->impurities}}</td>
                <td class="center">{{ optional(optional($details->quality_created)->sand)->weight}}</td>
                <td class="center">{{ optional(optional($details->quality_created)->sand)->percent}}</td>
                <td class="center">{{ number_format(optional(optional($details->quality_created)->sand)->parts_million)}}</td>
            </tr>
            <tr class="center">
                <td>
                    <div>TOTAL:</div>
                </td>
                <td class="center"></td>
                <td class="center"></td>
                <td class="center">{{ number_format(optional(optional($details->quality_created)->tie_tie)->percent + optional(optional($details->quality_created)->sand)->percent,4)}}</td>
                <td class="center">{{ number_format(optional(optional($details->quality_created)->tie_tie)->parts_million + optional(optional($details->quality_created)->sand)->parts_million,2)}}</td>
            </tr>
        </table>
        @php
            $remarks = optional($details->quality_created)->remarks ?? '';
            $wrapped = wordwrap($remarks, 120, "\n", true); 
            $lines = explode("\n", $wrapped);

            if (count($lines) < 2) {
                $lines[] = '&nbsp;';
            }
        @endphp

        <div>
            <strong>Remarks:</strong>
            @foreach ($lines as $line)
                <div class="remarks-line">{!! $line !!}</div>
            @endforeach
        </div>
            <div class="signature">
                <div class="signature-left">
                <span class="signature-label">Analyzed by:</span>
                <span class="signature-line"></span>
                <i>Seaweeds Analyst</i>
            </div>

            <div class="signature-right">
                <span class="signature-label">Verified by:</span>
                <span class="signature-line"></span>
                <i>Senior QC Supervisor</i>
            </div>
        </div>

    </div>
</body>

