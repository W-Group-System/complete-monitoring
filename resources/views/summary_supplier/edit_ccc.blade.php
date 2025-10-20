<div class="modal fade" id="editSummarySupplier{{ $supplier->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{url('ccc_supplier_summary_setup/edit/' .$supplier->id)}}" onsubmit="show()">
                @csrf 

                <div class="modal-body">
                    <div class="form-group mb-2">
                        <div class="form-group mb-2">
                            Supplier Name
                            <input type="text" class="form-control" value="{{ $supplier->CardName }}" readonly>
                        </div>
                        
                        <div class="form-group mb-2">
                            Supplier Code
                            <input type="text" class="form-control" value="{{ $supplier->CardCode }}" readonly>
                        </div>

                        {{-- <div class="form-group mb-2">
                            Name
                            <input type="text" name="short_name" class="form-control" value="{{ $supplier->Name }}">
                        </div> --}}
                        <div class="form-group mb-2">
                            Name
                           <select id="short_name_edit_{{ $supplier->id }}" 
                                    data-placeholder="Select Supplier" 
                                    name="short_name" 
                                    class="form-control" 
                                    style="width: 100%;" 
                                    required>
                                <option value=""></option>

                                @if($supplier->Name && !$shortNames->contains('Name', $supplier->Name))
                                    <option value="{{ $supplier->Name }}" selected>{{ $supplier->Name }}</option>
                                @endif
                                @foreach ($shortNames as $name)
                                    <option value="{{ $name->Name }}" {{ $supplier->Name === $name->Name ? 'selected' : '' }}>
                                        {{ $name->Name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="form-group mb-2">
                            Origin
                            <select id="supplier_origin" data-placeholder="Select Origin" name="supplier_origin" class="chosen-select" style="width: 100%;" required>
                                <option value=""></option>
                                <option value="ZAMBO" {{ $supplier->OriginGroup === 'ZAMBO' ? 'selected' : '' }}>ZAMBO</option>
                                <option value="MINDORO" {{ $supplier->OriginGroup === 'MINDORO' ? 'selected' : '' }}>MINDORO</option>
                                <option value="PALAWAN" {{ $supplier->OriginGroup === 'PALAWAN' ? 'selected' : '' }}>PALAWAN</option>
                                <option value="OTHERS" {{ $supplier->OriginGroup === 'OTHERS' ? 'selected' : '' }}>OTHERS</option>
                                <option value="IMPORT" {{ $supplier->OriginGroup === 'IMPORT' ? 'selected' : '' }}>IMPORT</option>
                                <option value="ANTIQUE" {{ $supplier->OriginGroup === 'ANTIQUE' ? 'selected' : '' }}>ANTIQUE</option>
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
    document.addEventListener('DOMContentLoaded', function(){
        $('#short_name_edit_{{ $supplier->id }}').select2({
            tags: true,
            placeholder: "Select or type a product",
            allowClear: true,
            dropdownParent: $('#editSummarySupplier{{ $supplier->id }}')
        });
    });
</script>