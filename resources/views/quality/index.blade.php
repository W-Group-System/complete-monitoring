@extends('layouts.header')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="card-body">
                    <h4 class="ibox-title">
                        <form method="GET" action="{{ url('/quality') }}" class="form-inline" style="margin-bottom: 15px;" onsubmit="show()">
                            <div class="form-group">
                                <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </h4>
                    <div class="ibox-content">
                        <div class="wrapper wrapper-content animated fadeIn">
                            <div class="row">
                                <div class="table-responsive" >
                                    <table class="table table-striped table-bordered table-hover tablewithSearch" >
                                        <thead>
                                             <tr>
                                                <th>Action</th>
                                                <th>GRPO</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Source</th>
                                                <th>Status</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($grpos as $grpo)
                                            <tr> 
                                                <td style="center">
                                                    @if ($grpo->quality_created)
                                                        <button type="button" class="btn btn-success btn-rounded" data-toggle="modal" data-target="#editQuality{{ $grpo->DocNum }}">Edit</button>
                                                        <a target='_blank' href="{{ url('print_qiality_report', $grpo->DocNum) }}" class="btn btn-danger btn-rounded" >Print</a>
                                                    @else 
                                                        <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#editQuality{{ $grpo->DocNum }}">Edit</button>
                                                    @endif
                                                </td>
                                                <td>{{ $grpo->DocNum }}</td>
                                                <td>{{ $grpo->NumAtCard }}</td>
                                                <td>{{ $grpo->CardName }}</td>
                                                <td>{{ $grpo->grpoLines->first()->ItemCode }}</td>
                                                <td>{{ optional($grpo->quality_created)->status }}</td>
                                                <td>{{ optional($grpo->quality_created)->approve_remarks }}</td>
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
@endsection