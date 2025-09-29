@extends('layouts.header')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="card-body">
                    <h4 class="ibox-title">
                        <form method="GET" action="{{ url('/approved_quality') }}" class="form-inline" style="margin-bottom: 15px;" onsubmit="show()">
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
                                    <table class="table table-striped table-bordered table-hover tablewithSearch" >
                                        <thead>
                                             <tr>
                                                <th>Action</th>
                                                <th>GRPO</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Source</th>
                                                <th>Status</th>
                                                <th>Returned By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($grpos as $grpo)
                                            <tr> 
                                                <td style="center">
                                                    @if ($grpo->quality_created)
                                                        <input type="hidden" class="qualityId" value="{{ $grpo->quality_created->id }}">
                                                        @if (request()->routeIs('approved_quality'))
                                                            <button type="button" class="btn btn-success btn-rounded" data-toggle="modal" data-target="#editQuality{{ $grpo->DocNum }}">Edit</button>
                                                            <a target='_blank' href="{{ url('print_quality_report', $grpo->DocNum) }}" class="btn btn-danger btn-rounded" >View</a>
                                                        @elseif (request()->routeIs('ccc_approved_quality'))
                                                            <button type="button" class="btn btn-success btn-rounded" data-toggle="modal" data-target="#editQualityCcc{{ $grpo->DocNum }}">Edit</button>
                                                            <a target='_blank' href="{{ url('ccc_print_quality_report', $grpo->DocNum) }}" class="btn btn-danger btn-rounded" >View</a>
                                                        @endif
                                                    @else 
                                                        <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#editQuality{{ $grpo->DocNum }}">Edit</button>
                                                    @endif
                                                </td>
                                                <td>{{ $grpo->DocNum }}</td>
                                                <td>{{ $grpo->NumAtCard }}</td>
                                                <td>{{ $grpo->CardName }}</td>
                                                <td>{{ $grpo->grpoLines->first()->ItemCode }}</td>
                                                <td>{{ $grpo->quality_created->status }}</td>
                                                <td>{{ optional(optional($grpo->quality_created)->approvedBy)->name }}</td>
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
    @if (request()->routeIs('approved_quality'))
        @include('quality.edit')
    @elseif (request()->routeIs('ccc_approved_quality'))
        @include('quality.editccc')
    @endif
@endforeach

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
</script>
@endsection
