@extends('layouts.header')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="card-body">
                    <h4 class="ibox-title">
                        Summary Suppliers
                        <button type="button" class="btn btn-md btn-outline-primary"  data-toggle="modal" data-target="#NewGroup">New</button>
                    </h4>
                    <div class="ibox-content">
                        <div class="wrapper wrapper-content animated fadeIn">
                            <div class="row">
                                <div class="table-responsive" >
                                    <table class="table table-striped table-bordered table-hover tablewithSearch" >
                                        <thead>
                                             <tr>
                                                <th>Action</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Short Name</th>
                                                <th>Source</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($suppliers as $supplier)
                                            <tr> 
                                                <td style="center">
                                                    <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#editSummarySupplier{{ $supplier->id }}"> Edit</button>
                                                    <form action="{{ url('deleteSetup', $supplier->id) }}" 
                                                        method="POST" 
                                                        style="display: inline-block;"
                                                        onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-rounded">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>{{ $supplier->CardCode }}</td>
                                                <td>{{ $supplier->CardName }}</td>
                                                <td>{{ $supplier->Name }}</td>
                                                <td>{{ $supplier->OriginGroup }}</td>
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
</div>
@include('summary_supplier.create')
@foreach ($suppliers as $supplier)
    @include('summary_supplier.edit')
@endforeach
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error!',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#d33'
        });
    @endif

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session("success") }}',
            confirmButtonColor: '#3085d6'
        });
    @endif
</script>
@endsection