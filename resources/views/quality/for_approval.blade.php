@extends('layouts.header')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="card-body">
                    <h4 class="ibox-title">
                        <form method="GET" action="{{ url('/quality_approval') }}" class="form-inline" style="margin-bottom: 15px;" onsubmit="show()">
                            <div class="form-group">
                                <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </h4>
                    <div class="ibox-content">
                        <div class="wrapper wrapper-content animated fadeIn">
                            <div class="row">
                                <div class="table-responsive">
                                    @if($grpos->filter(function($g) { return $g->quality_created !== null; })->count())
                                        <button id="approveAllBtn" class="btn btn-success mb-2">Approve All</button>
                                    @endif
                                    <table class="table table-striped table-bordered table-hover tablewithSearch" >
                                        <thead>
                                             <tr>
                                                <th>Action</th>
                                                <th>GRPO</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Source</th>
                                                <th>Status</th>
                                                <th>Requested By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($grpos as $grpo)
                                            <tr> 
                                                <td style="center">
                                                    @if ($grpo->quality_created)
                                                        <input type="hidden" class="qualityId" value="{{ $grpo->quality_created->id }}">
                                                        {{-- <button type="button" class="btn btn-success btn-rounded" data-toggle="modal" data-target="#editQuality{{ $grpo->DocNum }}">Edit</button> --}}
                                                        <a target='_blank' href="{{ url('print_qiality_report', $grpo->DocNum) }}" class="btn btn-danger btn-rounded" >View</a>
                                                        <button type="button" class="btn btn-info btn-rounded approveQuality" data-id="{{ $grpo->quality_created->id }}">Approve</button>
                                                        <button type="button" class="btn btn-warning btn-rounded disapproveQuality" data-id="{{ $grpo->quality_created->id }}">Return</button>
                                                    @else 
                                                        <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#editQuality{{ $grpo->DocNum }}">Edit</button>
                                                    @endif
                                                </td>
                                                <td>{{ $grpo->DocNum }}</td>
                                                <td>{{ $grpo->NumAtCard }}</td>
                                                <td>{{ $grpo->CardName }}</td>
                                                <td>{{ $grpo->grpoLines->first()->ItemCode }}</td>
                                                <td>{{ $grpo->quality_created->status }}</td>
                                                <td>{{ optional(optional($grpo->quality_created)->user)->name }}</td>
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
@foreach ($grpos as $grpo)
@include('quality.edit')
@endforeach

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(".approveQuality").on('click', function(){
        var qualityId = $(this).data('id');
        var approveUrl = "{{ url('ApproveQuality') }}/" + qualityId;

        Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Approved!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: approveUrl,
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            location.reload();
                        });
                    }
                })
            }
        });
    })
    $(".disapproveQuality").on('click', function() {
        var qualityId = $(this).data('id');
        var disapproveUrl = "{{ url('DisapproveQuality') }}/" + qualityId;

        Swal.fire({
            title: "Returned Quality Result",
            text: "Please provide your reason for return.",
            icon: "warning",
            input: 'textarea',
            inputPlaceholder: 'Enter your remarks here...',
            inputAttributes: {
                'aria-label': 'Type your remarks here'
            },
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Return",
            cancelButtonText: "Cancel",
            preConfirm: (approve_remarks) => {
                if (!approve_remarks) {
                    Swal.showValidationMessage("Remarks are required");
                } else {
                    return approve_remarks;
                }
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                $.ajax({
                    type: "POST",
                    url: disapproveUrl,
                    data: { approve_remarks: result.value },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Returned',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: 'An error occurred while returning.',
                        });
                    }
                });
            }
        });
    });

    document.getElementById('approveAllBtn').addEventListener('click', function () {
        let ids = [];

        document.querySelectorAll('.qualityId').forEach(function (input) {
            ids.push(input.value);
        });

        if (ids.length === 0) {
            Swal.fire("No items to approve", "", "info");
            return;
        }

        Swal.fire({
            title: "Are you sure?",
            text: "You are about to approve all quality records.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            confirmButtonText: "Yes, approve all!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ url('ApproveAllQuality') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ ids: ids })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success){
                        Swal.fire("Approved!", "All selected records have been approved.", "success")
                            .then(() => location.reload());
                    } else {
                        Swal.fire("Error", "Something went wrong.", "error");
                    }
                })
                .catch(() => Swal.fire("Error", "Something went wrong.", "error"));
            }
        });
    });
</script>
@endsection
