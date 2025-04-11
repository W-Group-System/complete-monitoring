@extends('layouts.header')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Roles List</h5>
                    <div class="ibox-tools">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#add_role"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add</button>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="wrapper wrapper-content animated fadeIn">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered datatables-sample">
                                    <thead>
                                        <tr>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($roles as $role)
                                            <tr>
                                                <td>{{$role->role_name}}</td>
                                                <td>
                                                    @if ($role->status == 1)
                                                    Active
                                                    @else
                                                    Inactive
                                                    @endif
                                                </td>
                                                <td>{{$role->created_at}}</td>
                                                <td></td>
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

@include('roles.create')
@endsection