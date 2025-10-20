@extends('layouts.header')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="card-body">
                    <h4 class="ibox-title">
                        <form action="{{ url('new_approver_setup') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label>User</label>
                                    <select name="user_id" class="form-control" required>
                                        <option value="">-- Select User --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Level</label>
                                    <input type="number" name="level" class="form-control" min="1" required>
                                </div>
                                <div class="col-md-3">
                                    <label>Department (optional)</label>
                                    <input type="text" name="department" class="form-control">
                                </div>
                                <div class="col-md-3 d-flex align-items-end" style="margin-top:20px;">
                                    <button type="submit" class="btn btn-primary w-100">Add Approver</button>
                                </div>
                            </div>
                        </form>
                    </h4>
                    <div class="ibox-content">
                        <div class="wrapper wrapper-content animated fadeIn">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover tablewithSearch" >
                                        <thead>
                                             <tr>
                                                <tr>
                                                    <th>Level</th>
                                                    <th>User</th>
                                                    <th>Department</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($setups as $setup)
                                                <tr>
                                                    <td>{{ $setup->level }}</td>
                                                    <td>{{ $setup->user->name }}</td>
                                                    <td>{{ $setup->department ?? '-' }}</td>
                                                    <td>
                                                        @if ($setup->status === "Active")
                                                            Active
                                                        @else
                                                            Inactive
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($setup->status == "Active")
                                                            <form method="post" action="{{ url('deactivate-approver/'.$setup->id) }}" onsubmit="show()"  style="display: inline-block;">
                                                                @csrf 
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fa fa-ban"></i>
                                                                </button>
                                                            </form>
                                                            @else
                                                            <form method="post" action="{{ url('activate-approver/'.$setup->id) }}" onsubmit="show()"  style="display: inline-block;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-primary">
                                                                    <i class="fa fa-check"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="4" class="text-center">No approvers set yet.</td></tr>
                                            @endforelse
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
@endsection
