<?php

namespace App\Http\Controllers;

use App\OCRD;
use App\OCRD_CCC;
use App\OPDN;
use App\OPOR;
use App\SummarySupplier;
use App\SummarySupplier_CCC;
use App\SummarySuppliersCcc;
use App\SWDelIns;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class SummaryController extends Controller
{
    // public function index(Request $request)
    // {
    //     $year = $request->input('year') ?? date('Y');

    //     $fromDate = Carbon::createFromFormat('Y', $year)->startOfYear();
    //     $endDate = Carbon::createFromFormat('Y', $year)->endOfYear();

    //     $summaryWhi = SummarySupplier::with(['opdn' => function ($opdnQuery) use ($fromDate, $endDate) {
    //         $opdnQuery->whereBetween('DocDate', [$fromDate, $endDate])
    //                 ->where('CANCELED', '!=', 'Y')
    //                 ->whereHas('purchaseOrders', function ($query) use ($fromDate, $endDate) {
    //                     $query->whereBetween('OPOR.DocDate', [$fromDate, $endDate]);
    //                 });
    //     }])->get();

    //     $summaryCcc = SummarySuppliersCcc::with(['opdn' => function ($opdnQuery) use ($fromDate, $endDate) {
    //         $opdnQuery->whereBetween('DocDate', [$fromDate, $endDate])
    //                 ->where('CANCELED', '!=', 'Y')
    //                 ->whereHas('purchaseOrders', function ($query) use ($fromDate, $endDate) {
    //                     $query->whereBetween('OPOR.DocDate', [$fromDate, $endDate]);
    //                 });
    //     }])->get();

    //     // $mergedSuppliers = $summaryWhi->concat($summaryCcc);

    //     $mergedSuppliers = $summaryWhi
    //     ->concat($summaryCcc)
    //     ->groupBy(function ($item) {
    //         return strtoupper(trim($item->Name)) . '|' . strtoupper(trim($item->OriginGroup)); 
    //     })
    //     ->map(function ($group) {
    //         $merged = $group->first();

    //         $merged->setRelation('opdn', $group->flatMap->opdn);

    //         return $merged;
    //     })
    //     ->values();
        
    //     // $groupedSuppliers = $mergedSuppliers->groupBy('Name'); 

    //     // $finalSuppliers = $groupedSuppliers->map(function ($group) {
    //     //     $first = $group->first();
    //     //     $first->opdn = $group->flatMap->opdn; 
    //     //     return $first;
    //     // })->values();
    //     // SummarySupplier::orderBy('OriginGroup')->chunk(500, function ($supplierChunk) use (&$suppliers, $fromDate, $endDate) {
    //     //     $chunkedSuppliers = $supplierChunk->load(['opdn' => function ($opdnQuery) use ($fromDate, $endDate) {
    //     //         $opdnQuery->whereBetween('DocDate', [$fromDate, $endDate])
    //     //                   ->where('CANCELED', '!=', 'Y')
    //     //                   ->whereHas('purchaseOrders', function ($query) use ($fromDate, $endDate) {
    //     //                       $query->whereBetween('OPOR.DocDate', [$fromDate, $endDate]);
    //     //                   });
    //     //     }]);
        
    //     //     $suppliers = $suppliers->merge($chunkedSuppliers);
    //     // });
        
    //     if ($request->is('spi_summary')) {
    //         return view('summary.spi_summary', compact('suppliers'));
    //     }

    //     // return view('summary.cott_summary', compact('suppliers'));
    //     return view('summary.cott_summary', [
    //         'suppliers' => $mergedSuppliers,
    //     ]);
    // }

    public function index(Request $request)
    {
        $year = $request->input('year') ?? date('Y');

        $fromDate = Carbon::createFromFormat('Y', $year)->startOfYear();
        $endDate = Carbon::createFromFormat('Y', $year)->endOfYear();

        $mergedSuppliers = collect();

        SummarySupplier::with(['opdn' => function ($opdnQuery) use ($fromDate, $endDate) {
            $opdnQuery->whereBetween('DocDate', [$fromDate, $endDate])
                ->where('CANCELED', '=', 'N');
        }])->chunk(500, function ($chunk) use (&$mergedSuppliers) {
            $mergedSuppliers = $mergedSuppliers->concat($chunk);
        });

        SummarySuppliersCcc::with(['opdn' => function ($opdnQuery) use ($fromDate, $endDate) {
            $opdnQuery->whereBetween('DocDate', [$fromDate, $endDate])
                ->where('CANCELED', '=', 'N');
        }])->chunk(500, function ($chunk) use (&$mergedSuppliers) {
            $mergedSuppliers = $mergedSuppliers->concat($chunk);
        });

        $mergedSuppliers = $mergedSuppliers
            ->groupBy(function ($item) {
                return strtoupper(trim($item->Name)) . '|' . strtoupper(trim($item->OriginGroup));
            })
            ->map(function ($group) {
                $merged = $group->first();
                $merged->setRelation('opdn', $group->flatMap->opdn);
                return $merged;
            })
            ->sortBy(function ($item) {
                return $item->OriginGroup; 
            })
            ->values();

        if ($request->is('spi_summary')) {
            return view('summary.spi_summary', [
                'suppliers' => $mergedSuppliers
            ]);
        }

        return view('summary.cott_summary', [
            'suppliers' => $mergedSuppliers
        ]);
    }


    public function summary_suppliers(Request $request)
    {
        $suppliers = SummarySupplier::all();
        $ocrds = OCRD::all();
        return view('summary_supplier.index',compact('suppliers','ocrds'));
    }

    public function deleteSupplier($id)
    {
        $supplier = SummarySupplier::findOrFail($id);

        $supplier->delete();

        return redirect()->back()->with('success', 'Supplier deleted successfully.');
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
        $new_group->Name = $request->short_name;
        $new_group->OriginGroup = $request->supplier_origin;
        $new_group->save();
        return back()->with('success', 'Supplier created successfully.');
    }

    public function supplier_summary_edit(Request $request, $id)

    {

        $edit_supplier = SummarySupplier::findOrFail($id);
        $edit_supplier->OriginGroup = $request->supplier_origin;
        $edit_supplier->Name = $request->short_name;
        $edit_supplier->save();
        return back()->with('success', 'Supplier edited successfully.');
    }

    public function ccc_summary_suppliers(Request $request)
    {
        $suppliers = SummarySuppliersCcc::all();
        $ocrds = OCRD_CCC::all();
        $shortNames = SummarySupplier::all();
        return view('summary_supplier.index_ccc',compact('suppliers','ocrds', 'shortNames'));
    }

    public function ccc_supplier_summary_setup(Request $request)

    {
        $existing_group = SummarySuppliersCcc::where('CardCode', $request->supplier_code)->first();
        if ($existing_group) {
            return back()->withErrors(['CardCode' => 'The Supplier name must be unique.']);
        }
        $new_group = new SummarySuppliersCcc;
        $new_group->CardName = $request->supplier_name;
        $new_group->CardCode = $request->supplier_code;
        $new_group->Name = $request->short_name;
        $new_group->OriginGroup = $request->supplier_origin;
        $new_group->save();
        return back()->with('success', 'Supplier created successfully.');
    }
    public function ccc_supplier_summary_edit(Request $request, $id)

    {

        $edit_supplier = SummarySuppliersCcc::findOrFail($id);
        $edit_supplier->OriginGroup = $request->supplier_origin;
        $edit_supplier->Name = $request->short_name;
        $edit_supplier->save();
        return back()->with('success', 'Supplier edited successfully.');
    }
    public function deleteCccSupplier($id)
    {
        $supplier = SummarySuppliersCcc::findOrFail($id);

        $supplier->delete();

        return redirect()->back()->with('success', 'Supplier deleted successfully.');
    }
    

}