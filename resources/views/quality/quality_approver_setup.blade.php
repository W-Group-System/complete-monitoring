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
                                <div class="col-md-3 d-flex align-items-end">
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
                                                    {{-- <td>
                                                        <form action="{{ route('approver-setup.destroy', $setup->id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Remove this approver?')">
                                                                Remove
                                                            </button>
                                                        </form>
                                                    </td> --}}
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
