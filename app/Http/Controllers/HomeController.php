<?php

namespace App\Http\Controllers;

use App\OCRD;
use App\OPDN;
use App\OPOR;
use App\SWDelIns;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $supplierFilter = $request->input('supplier');
        $suppliers = OCRD::all();
        
        $grpos = OPDN::with(['grpoLines.purchaseOrder' , 'freightPoInvoice.DeductionLines'])
        ->whereHas('purchaseOrders', function ($query) use ($supplierFilter, $fromDate, $endDate) {
            $query->whereBetween('OPOR.DocDate', [$fromDate, $endDate]);
        })
        ->where('OPDN.CardName',  $supplierFilter)
        ->where('OPDN.CANCELED',  '!=','Y')
        ->get()
        ->map(function ($grpo) {
            $poNumbers = $grpo->grpoLines->pluck('purchaseOrder.DocNum')->unique()->implode(' / ');
            $poDocDate = $grpo->grpoLines->pluck('purchaseOrder.DocDate')->unique()->implode(' / ');
            $grpo->Combined_po_numbers = $poNumbers;
            $grpo->PoDate = $poDocDate;
            return $grpo;
        });
        return view('home.index', compact('suppliers', 'grpos'));
    }

    public function filter(Request $request) 
    {
        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date = Carbon::parse($request->end_date)->endOfDay();

        $cotts = $this->dateFilter($start_date, $end_date);
        
        return view('cott.index', ['cotts' => $cotts, 'start_date' => $start_date, 'end_date' => $end_date]);  
    }

}