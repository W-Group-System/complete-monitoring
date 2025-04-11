<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::get();
        return view('roles.roles', compact('roles'));
    }

    public function store(Request $request)
    {
        $new_role = new Role;
        $new_role->role_name = $request->name; 
        $new_role->status = 1; 
        $new_role->save();
        return back();
    }
}