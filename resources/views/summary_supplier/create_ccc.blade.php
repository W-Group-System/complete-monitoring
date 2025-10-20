<style>
    .select2-dropdown {
        z-index: 9999 !important;
    }
</style>
<div class="modal fade" id="NewGroupCCC" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit">Add Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('ccc_supplier_summary_setup')}}" onsubmit="show()">
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
                            Name
                            <select id="short_names" data-placeholder="Select Supplier" name="short_name" class="form-control" style="width: 100%;" required>
                                <option value=""></option>
                                @foreach ($shortNames as $name)
                                    <option value="{{ $name->Name }}">{{ $name->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="form-group mb-2">
                            Name
                            <input type="text" name="short_name" class="form-control" required>
                        </div> --}}

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
        const descriptionSelect = document.getElementById('short_names'); 

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

        $('#short_names').select2({
            tags: true,
            placeholder: "Select or type a product",
            allowClear: true,
            dropdownParent: $('#NewGroupCCC')
        });

    });
</script>
