<?php

namespace App\Http\Controllers;

use App\QualityApproverSetup;
use App\User;
use Illuminate\Http\Request;

class QualityApproverSetupController extends Controller
{
    public function index(Request $request)
    {
        $setups = QualityApproverSetup::with('user')->orderBy('level')->get();
        $users  = User::all(); 
        return view('quality.quality_approver_setup', compact('setups', 'users'));
    }
    public function store(Request $request)

    {
        $existing_level = QualityApproverSetup::where('level', $request->level)->first();
        if ($existing_level) {
            return back()->withErrors(['Level' => 'Level already exists.']);
        }
        $approver = new QualityApproverSetup;
        $approver->user_id = $request->user_id;
        $approver->level = $request->level;
        $approver->department = $request->department;
        $approver->save();
        return back()->with('success', 'Supplier created successfully.');
    }
}
