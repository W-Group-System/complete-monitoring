<?php

namespace App\Http\Controllers;

use App\OCRD;
use App\OPDN;
use App\OPOR;
use App\SummarySupplier;
use App\SWDelIns;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class SummaryController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year') ?? date('Y');

        $fromDate = Carbon::createFromFormat('Y', $year)->startOfYear();
        $endDate = Carbon::createFromFormat('Y', $year)->endOfYear();

        $suppliers = collect(); 
        SummarySupplier::orderBy('OriginGroup')->chunk(500, function ($supplierChunk) use (&$suppliers, $fromDate, $endDate) {
            $chunkedSuppliers = $supplierChunk->load(['opdn' => function ($opdnQuery) use ($fromDate, $endDate) {
                $opdnQuery->whereBetween('DocDate', [$fromDate, $endDate])
                          ->where('CANCELED', '!=', 'Y')
                          ->whereHas('purchaseOrders', function ($query) use ($fromDate, $endDate) {
                              $query->whereBetween('OPOR.DocDate', [$fromDate, $endDate]);
                          });
            }]);
        
            $suppliers = $suppliers->merge($chunkedSuppliers);
        });
        
        if ($request->is('spi_summary')) {
            return view('summary.spi_summary', compact('suppliers'));
        }

        return view('summary.cott_summary', compact('suppliers'));
    }

    public function summary_suppliers(Request $request)
    {
        $suppliers = SummarySupplier::all();
        $ocrds = OCRD::all();
        return view('summary_supplier.index',compact('suppliers','ocrds'));
    }

    public function supplier_summary_setup(Request $request)

    {
        $existing_group = SummarySupplier::where('CardCode', $request->supplier_code)->first();
        if ($existing_group) {
            return back()->withErrors(['CardCode' => 'The Supplier name must be unique.']);
        }
        $new_group = new SummarySupplier;
        $new_group->CardName = $request->supplier_name;
        $new_group->CardCode = $request->supplier_code;
        $new_group->OriginGroup = $request->supplier_origin;
        $new_group->save();
        return back()->with('success', 'Supplier created successfully.');
    }

}