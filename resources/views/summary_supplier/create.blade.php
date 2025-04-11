<div class="modal fade" id="NewGroup" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSrf">Add Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('supplier_summary_setup')}}" onsubmit="show()">
                @csrf 

                <div class="modal-body">
                    <div class="form-group mb-2">
                        <div class="form-group mb-2">
                            Supplier Name
                            <select id="supplier_name" data-placeholder="Select Supplier" name="supplier_name" class="chosen-select" style="width: 100%;" required>
                                <option value=""></option>
                                @foreach ($ocrds as $ocrd)
                                    <option value="{{ $ocrd->CardName }}" data-code="{{ $ocrd->CardCode }}">{{ $ocrd->CardName }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group mb-2">
                            Supplier Code
                            <input type="text" id="supplier_code" name="supplier_code" class="form-control" required readonly>
                        </div>

                        <div class="form-group mb-2">
                            Origin
                            <select id="supplier_origin" data-placeholder="Select Origin" name="supplier_origin" class="chosen-select" style="width: 100%;" required>
                                <option value=""></option>
                                <option value="ZAMBO">ZAMBO</option>
                                <option value="MINDORO">MINDORO</option>
                                <option value="PALAWAN">PALAWAN</option>
                                <option value="OTHERS">OTHERS</option>
                                <option value="IMPORT">IMPORT</option>
                                <option value="ANTIQUE">ANTIQUE</option>
                            </select>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const supplierNameSelect = document.getElementById('supplier_name');
        const supplierCodeInput = document.getElementById('supplier_code');
        
        supplierNameSelect.addEventListener('change', function() {
            const selectedOption = supplierNameSelect.options[supplierNameSelect.selectedIndex];
            
            const cardCode = selectedOption.getAttribute('data-code');
            
            supplierCodeInput.value = cardCode;
        });

        $(".chosen-select").on('change', function() {
            const selectedOption = supplierNameSelect.options[supplierNameSelect.selectedIndex];
            const cardCode = selectedOption.getAttribute('data-code');
            supplierCodeInput.value = cardCode;
        });
    });
</script>
