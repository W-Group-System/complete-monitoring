<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::get();
        $roles = Role::get();
        return view('users.users', compact('users','roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);
        $new_user = new User();
        $new_user->name =  $request->name;
        $new_user->email = $request->email;
        $new_user->role_id = $request->role;
        $new_user->status = 1;
        $new_user->password = bcrypt('wgroup1nc');
        $new_user->save();
        return back();
    }
}