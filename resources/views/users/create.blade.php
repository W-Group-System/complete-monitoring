<div class="modal fade" id="add_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="POST" action="{{url('new_user')}}" autocomplete="off">
        @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Add User</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label>Name</label>
                            <input name="name" class="form-control" type="text" placeholder="Enter Name" required>
                        </div>
                        <div class="col-12">
                            <label>Role</label>
                            <select name="role"  class="form-control selectpicker" title="Select Position" required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label>Email Address</label>
                            <input name="email" class="form-control" type="text" placeholder="Enter Email Address" required>
                        </div>
                    </div>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </form>
</div>