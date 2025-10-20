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
        $existing_level = QualityApproverSetup::where('level', $request->level)
        ->where('status', 'Active') 
        ->first();

        if ($existing_level) {
            return back()->withErrors(['Level' => 'Level already exists with an active approver.']);
        }
        $approver = new QualityApproverSetup;
        $approver->user_id = $request->user_id;
        $approver->level = $request->level;
        $approver->department = $request->department;
        $approver->status = 'Active';
        $approver->save();
        return back()->with('success', 'Supplier created successfully.');
    }
    public function activate($id)
    {
        $approver = QualityApproverSetup::findOrFail($id);

        $existing = QualityApproverSetup::where('level', $approver->level)
            ->where('status', 'Active')
            ->where('id', '!=', $approver->id) 
            ->first();

        if ($existing) {
            return back()->withErrors([
                'Level' => 'This level is already assigned to another active approver.'
            ]);
        }
        $approver->status = 'Active';
        $approver->save();

        return back();
    }
    public function deactivate($id)
    {
        $approver = QualityApproverSetup::findOrFail($id);
        $approver->status = 'Inactive';
        $approver->save();

        return back();
    }
}
