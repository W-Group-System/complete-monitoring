<div class="modal fade" id="editQuality{{ $grpo->DocNum }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        
            <div class="modal-header">
                <h5 class="modal-title">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form method="POST" action="{{ url('quality/edit/' . $grpo->DocNum) }}" onsubmit="show()">
                @csrf
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-9 mb-2">
                            <label>SW Code</label>
                            <input type="text" class="form-control" value="{{ $grpo->NumAtCard }}" readonly>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>DR/RR No</label>
                            <input type="text" class="form-control" name="dr_rr" value="{{ optional($grpo->quality_created)->dr_rr }}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Date of Delivery</label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($grpo->DocDate)->format('Y-m-d') }}" readonly>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Container/Plate No.</label>
                            <input type="text" class="form-control" value="{{ $grpo->U_ContainerNo }}" readonly>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Supplier</label>
                            <input type="text" class="form-control" value="{{ $grpo->CardName }}" readonly>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Source</label>
                            <input type="text" class="form-control" 
                                value="{{ !empty($grpo->U_Origin) ? $grpo->U_Origin : $grpo->U_Country }}" 
                                readonly>
                        </div>
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
                        <div class="col-md-6 mb-2">
                            <label>QTY Delivered</label>
                            <input type="text" class="form-control" value="{{ number_format($ArrivalWt, 2, '.', ',') }}"  readonly>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Number of Bags</label>
                            <input type="text" class="form-control" value="{{ $NoOfBags }}" readonly>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Location/BIN</label>
                            <input type="text" class="form-control" value="{{ optional($grpo->quality_created)->location_bin }}" name="location_bin">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="d-block mb-1">Seaweeds</label>
                            <div style="display: flex; gap: 30px; flex-wrap: nowrap; align-items: center;">
                                <div class="form-check" style="margin: 0;">
                                    <input class="form-check-input" type="radio" name="seaweeds" id="cottonii" value="Eucheuma Cottonii"
                                    {{ optional($grpo->quality_created)->seaweeds == 'Eucheuma Cottonii' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cottonii">Eucheuma Cottonii</label>
                                </div>
                                <div class="form-check" style="margin: 0;">
                                    <input class="form-check-input" type="radio" name="seaweeds" id="spinosum" value="Eucheuma Spinosum"
                                    {{ optional($grpo->quality_created)->seaweeds == 'Eucheuma Spinosum' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="spinosum">Eucheuma Spinosum</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="d-block mb-1">Haghag</label>
                            <div style="display: flex; gap: 30px; flex-wrap: nowrap; align-items: center;">
                                <div class="form-check" style="margin: 0;">
                                    <input class="form-check-input" type="radio" name="haghag" id="haghag-10" value="10%"
                                    {{ optional($grpo->quality_created)->haghag == '10%' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="haghag-10">10%</label>
                                </div>
                                <div class="form-check" style="margin: 0;">
                                    <input class="form-check-input" type="radio" name="haghag" id="haghag-100" value="100%"
                                    {{ optional($grpo->quality_created)->haghag == '100%' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="haghag-100">100%</label>
                                </div>
                                <div class="form-check" style="margin: 0;">
                                    <input class="form-check-input" type="radio" name="haghag" id="haghag-na" value="N/A"
                                    {{ optional($grpo->quality_created)->haghag == 'N/A' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="haghag-na">N/A</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Ocular MC</label>
                            <input type="text" class="form-control" value="{{ optional($grpo->quality_created)->ocular_mc }}" name="ocular_mc">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Agreed MC</label>
                            <input type="text" class="form-control" value="{{ optional($grpo->quality_created)->agreed_mc }}" name="agreed_mc">
                        </div>
                        <div class="col-md-6 mb-2">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Agreed Budget Yield</label>
                            <input type="text" class="form-control" value="{{ optional($grpo->quality_created)->budget_yield }}" name="budget_yield">
                        </div>
                    </div>
                    <div class="row">
                        <div style="margin-top: 30px">
                            <H4>I. PHYSICAL AND OCULAR INSPECTION</H4>
                            <div class="col-md-12 mb-2">
                                <div class="row mb-2 align-items-center">
                                    <div class="col-md-3">
                                        <label class="mb-0">Color of Seaweeds</label>
                                    </div>
                                    <div class="col-md-7">
                                        <div style="display: flex; flex-wrap: nowrap; gap: 15px;">
                                            @php
                                                $storedConditions = json_decode($grpo->quality_created->colors->condition ?? '[]', true);
                                            @endphp
                                            @foreach (['Purple', 'Brown', 'Black', 'Pink', 'Green', 'Yellow'] as $color)
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="checkbox" 
                                                        name="condition[]" 
                                                        id="{{ strtolower($color) }}" 
                                                        value="{{ $color }}"
                                                        {{ in_array($color, $storedConditions) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ strtolower($color) }}">{{ $color }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" name="remarks" value="{{ optional(optional($grpo->quality_created)->colors)->remarks }}" placeholder="Remarks">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="row mb-2 align-items-center">
                                    <div class="col-md-3">
                                        <label class="mb-0">Appearance</label>
                                    </div>
                                    <div class="col-md-7">
                                        <div style="display: flex; flex-wrap: nowrap; gap: 15px;">
                                            @php
                                                $storedConditions = json_decode($grpo->quality_created->appearance->condition ?? '[]', true);
                                            @endphp
                                            @foreach (['Thick', 'Moderately Thick', 'Thin', 'Small', 'Medium', 'Giant'] as $appearance_condition)
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="checkbox" 
                                                        name="appearance_condition[]" 
                                                        id="{{ strtolower($appearance_condition) }}" 
                                                        value="{{ $appearance_condition }}"
                                                        {{ in_array($appearance_condition, $storedConditions) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ strtolower($appearance_condition) }}">{{ $appearance_condition }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" value="{{ optional(optional($grpo->quality_created)->appearance)->remarks }}" name="appearance_remarks" placeholder=" Remarks">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="row mb-2 align-items-center">
                                    <div class="col-md-3">
                                        <label class="mb-0">Ice-ice</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div style="display: flex; gap: 30px; flex-wrap: nowrap; align-items: center;">
                                            <div class="form-check" style="margin: 0;">
                                                <input class="form-check-input" type="radio" name="ice_ice" id="Present" value="Present"
                                                {{ optional($grpo->quality_created)->ice == 'Present' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="Present">Present</label>
                                            </div>
                                            <div class="form-check" style="margin: 0;">
                                                <input class="form-check-input" type="radio" name="ice_ice" id="Absent" value="Absent"
                                                {{ optional($grpo->quality_created)->ice == 'Absent' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="Absent">Absent</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="row mb-2 align-items-center">
                                    <div class="col-md-3">
                                        <label class="mb-0">Moss</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div style="display: flex; gap: 30px; flex-wrap: nowrap; align-items: center;">
                                            <div class="form-check" style="margin: 0;">
                                                <input class="form-check-input" type="radio" name="moss" id="Present" value="Present"
                                                {{ optional($grpo->quality_created)->moss == 'Present' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="Present">Present</label>
                                            </div>
                                            <div class="form-check" style="margin: 0;">
                                                <input class="form-check-input" type="radio" name="moss" id="Absent" value="Absent"
                                                {{ optional($grpo->quality_created)->moss == 'Absent' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="Absent">Absent</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div style="margin-top: 30px">
                            <H4>II. PHYSICO-CHEMICAL TESTING</H4>
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
                                $testingMap = collect(optional($grpo->quality_created)->chemical_testings)
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
                                <div class="col-md-12 mb-2">
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-md-3">
                                            @if (!empty($param))
                                                <input type="text" class="form-control" name="quality_parameter[]" value="{{ $param }}" readonly>
                                            @else
                                                <input type="hidden" name="quality_parameter[]" value="">
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="quality_specification[]" value="{{ $item['spec'] }}" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="quality_result[]" value="{{ $stored->result ?? '' }}">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="quality_remarks[]" placeholder="Remarks" value="{{ $stored->remarks ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div style="margin-top: 30px">
                            <H4>III. FOREIGN MATERIAL REPORT </H4>
                            <div class="col-md-12 mb-2">
                                <div class="row mb-2 align-items-center">
                                    <div class="col-md-4">
                                        <label class="d-block mb-1">1. Tie-tie/FOM</label>
                                        <div style="display: flex; gap: 30px; flex-wrap: nowrap; align-items: center;">
                                            <div class="form-check" style="margin: 0;">
                                                <input class="form-check-input" type="radio" name="foms" id="foms" value="Present"
                                                {{ optional(optional($grpo->quality_created)->tie_tie)->foreign_matter == 'Present' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="foms">Present</label>
                                            </div>
                                            <div class="form-check" style="margin: 0;">
                                                <input class="form-check-input" type="radio" name="foms" id="foms" value="Absent"
                                                {{ optional(optional($grpo->quality_created)->tie_tie)->foreign_matter == 'Absent' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="foms">Absent</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div style="display: flex; flex-wrap: nowrap; gap: 15px;">
                                            <input type="text" class="form-control" value="{{ optional(optional($grpo->quality_created)->tie_tie)->impurities}}" name="foms_impurities" placeholder="Impurities (kg)">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div style="display: flex; flex-wrap: nowrap; gap: 15px;">
                                            <input type="text" class="form-control" value="{{ optional(optional($grpo->quality_created)->tie_tie)->weight}}" name="foms_weight" placeholder="Weigt of Sample">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div style="display: flex; flex-wrap: nowrap; gap: 15px;">
                                            <input type="text" class="form-control" name="foms_percent" value="{{ optional(optional($grpo->quality_created)->tie_tie)->percent}}" placeholder="Percent Impurities">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div style="display: flex; flex-wrap: nowrap; gap: 15px;">
                                            <input type="text" class="form-control" name="foms_parts" value="{{ optional(optional($grpo->quality_created)->tie_tie)->parts_million}}" placeholder="Parts per million">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="row mb-2 align-items-center">
                                    <div class="col-md-4">
                                        <label class="d-block mb-1">2. Sand/Salt</label>
                                        <div style="display: flex; gap: 30px; flex-wrap: nowrap; align-items: center;">
                                            <div class="form-check" style="margin: 0;">
                                                <input class="form-check-input" type="radio" name="salts" id="salts" value="Present"
                                                {{ optional(optional($grpo->quality_created)->sand)->foreign_matter == 'Present' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="salts">Present</label>
                                            </div>
                                            <div class="form-check" style="margin: 0;">
                                                <input class="form-check-input" type="radio" name="salts" id="salts" value="Absent"
                                                {{ optional(optional($grpo->quality_created)->sand)->foreign_matter == 'Absent' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="salts">Absent</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div style="display: flex; flex-wrap: nowrap; gap: 15px;">
                                            <input type="text" class="form-control" name="salts_impurities" value="{{ optional(optional($grpo->quality_created)->sand)->impurities}}" placeholder="Impurities (kg)">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div style="display: flex; flex-wrap: nowrap; gap: 15px;">
                                            <input type="text" class="form-control" name="salts_weight" value="{{ optional(optional($grpo->quality_created)->sand)->weight}}" placeholder="Weigt of Sample">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div style="display: flex; flex-wrap: nowrap; gap: 15px;">
                                            <input type="text" class="form-control" name="salts_percent" value="{{ optional(optional($grpo->quality_created)->sand)->percent}}" placeholder="Percent Impurities">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div style="display: flex; flex-wrap: nowrap; gap: 15px;">
                                            <input type="text" class="form-control" name="salts_parts" value="{{ optional(optional($grpo->quality_created)->sand)->parts_million}}" placeholder="Parts per million">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div style="margin-top: 30px">
                            <div class="col-md-12 mb-2">
                            <label>Remarks</label>
                            <input type="text" class="form-control" value="{{ optional($grpo->quality_created)->remarks}}" name="quality_tab_remarks" value="">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
                    <button class="btn btn-success" type="submit">Save</button>
                </div>
                
            </form>
        </div>
    </div>
</div>
