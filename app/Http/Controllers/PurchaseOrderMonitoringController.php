<?php

namespace App\Http\Controllers;

use App\OCRD;
use App\OCRD_CCC;
use App\OPOR;
use App\OPOR_CCC;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderMonitoringController extends Controller
{
    public function cottIndex(Request $request)
    {
        $fromDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $supplierFilter = $request->input('supplier', []);
        $companyFilter = $request->input('company', 'All');
        $whi_supplier = OCRD::get();
        $ccc_supplier = OCRD_CCC::get();

        $suppliers = $whi_supplier->concat($ccc_supplier);

        $pos = collect();

        if ($companyFilter === 'WHI' || $companyFilter === 'All') {
            $posWHI = OPOR::select([
                'OPOR.DocEntry',
                'OPOR.DocNum',
                'OPOR.CardCode',
                'OPOR.CardName',
                'OPOR.DocDate',
                'OPOR.DocTotal',
                'OPOR.DocCur',
                'OPOR.NumAtCard',
                'POR1.Quantity',
                'POR1.Price',
                DB::raw("'CAR' as Company"),
            ])
            ->join('POR1', 'OPOR.DocEntry', '=', 'POR1.DocEntry')
            ->whereBetween('OPOR.DocDate', [$fromDate, $endDate])
            ->when(!empty($supplierFilter), fn($q) => $q->whereIn('OPOR.CardName', $supplierFilter))
            ->where('OPOR.CANCELED',  '=','N')
            ->where('POR1.ItemCode', 'SWDCOTPHIL')
            ->distinct()
            ->get();
            // ->map(function ($grpo) {
            //     $poNumbers = $grpo->grpoLines->pluck('purchaseOrder.DocNum')->unique()->implode(' / ');
            //     $poDocDate = $grpo->grpoLines->pluck('purchaseOrder.DocDate')->unique()->implode(' / ');
            //     $grpo->Combined_po_numbers = $poNumbers;
            //     $grpo->PoDate = $poDocDate;
            //     return $grpo;
            // });
            $pos = $pos->concat($posWHI);
        }
        if ($companyFilter === 'CCC' || $companyFilter === 'All') {
            $posCCC = OPOR_CCC::select([
                'OPOR.DocEntry',
                'OPOR.DocNum',
                'OPOR.CardCode',
                'OPOR.CardName',
                'OPOR.DocDate',
                'OPOR.DocTotal',
                'OPOR.DocCur',
                'OPOR.NumAtCard',
                'POR1.Quantity',
                'POR1.Price',
                DB::raw("'CCC' as Company"),
            ])
            ->join('POR1', 'OPOR.DocEntry', '=', 'POR1.DocEntry')
            ->whereBetween('OPOR.DocDate', [$fromDate, $endDate])
            ->when(!empty($supplierFilter), fn($q) => $q->whereIn('OPOR.CardName', $supplierFilter))
            ->where('OPOR.CANCELED', '=', 'N')
            ->where('POR1.ItemCode', 'Seaweeds-COTTONII')
            ->distinct()
            ->get();
            // ->map(function ($grpo) {
            //     $grpo->Combined_po_numbers = $grpo->grpoLines->pluck('purchaseOrder.DocNum')->unique()->implode(' / ');
            //     $grpo->PoDate = $grpo->grpoLines->pluck('purchaseOrder.DocDate')->unique()->implode(' / ');
            //     return $grpo;
            // });
            $pos = $pos->concat($posCCC);
        }
        $pos = $pos->sortBy('DocDate')->values();
        return view('poMonitoring.cott.summary', compact('suppliers', 'pos', 'companyFilter'));
    }

    public function spiIndex(Request $request)
    {
        $fromDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $supplierFilter = $request->input('supplier', []);
        $companyFilter = $request->input('company', 'All');
        $whi_supplier = OCRD::get();
        $ccc_supplier = OCRD_CCC::get();

        $suppliers = $whi_supplier->concat($ccc_supplier);

        $pos = collect();

        if ($companyFilter === 'WHI' || $companyFilter === 'All') {
            $posWHI = OPOR::select([
                'OPOR.DocEntry',
                'OPOR.DocNum',
                'OPOR.CardCode',
                'OPOR.CardName',
                'OPOR.DocDate',
                'OPOR.DocTotal',
                'OPOR.DocCur',
                'OPOR.NumAtCard',
                'POR1.Quantity',
                'POR1.Price',
                DB::raw("'CAR' as Company"),
            ])
            ->join('POR1', 'OPOR.DocEntry', '=', 'POR1.DocEntry')
            ->whereBetween('OPOR.DocDate', [$fromDate, $endDate])
            ->when(!empty($supplierFilter), fn($q) => $q->whereIn('OPOR.CardName', $supplierFilter))
            ->where('OPOR.CANCELED',  '=','N')
            ->where('POR1.ItemCode', 'SWDSPIPHIL')
            ->distinct()
            ->get();
            // ->map(function ($grpo) {
            //     $poNumbers = $grpo->grpoLines->pluck('purchaseOrder.DocNum')->unique()->implode(' / ');
            //     $poDocDate = $grpo->grpoLines->pluck('purchaseOrder.DocDate')->unique()->implode(' / ');
            //     $grpo->Combined_po_numbers = $poNumbers;
            //     $grpo->PoDate = $poDocDate;
            //     return $grpo;
            // });
            $pos = $pos->concat($posWHI);
        }
        if ($companyFilter === 'CCC' || $companyFilter === 'All') {
            $posCCC = OPOR_CCC::select([
                'OPOR.DocEntry',
                'OPOR.DocNum',
                'OPOR.CardCode',
                'OPOR.CardName',
                'OPOR.DocDate',
                'OPOR.DocTotal',
                'OPOR.DocCur',
                'OPOR.NumAtCard',
                'POR1.Quantity',
                'POR1.Price',
                DB::raw("'CCC' as Company"),
            ])
            ->join('POR1', 'OPOR.DocEntry', '=', 'POR1.DocEntry')
            ->whereBetween('OPOR.DocDate', [$fromDate, $endDate])
            ->when(!empty($supplierFilter), fn($q) => $q->whereIn('OPOR.CardName', $supplierFilter))
            ->where('OPOR.CANCELED', '=', 'N')
            ->where('POR1.ItemCode', 'Seaweeds - SPINOSUM')
            ->distinct()
            ->get();
            // ->map(function ($grpo) {
            //     $grpo->Combined_po_numbers = $grpo->grpoLines->pluck('purchaseOrder.DocNum')->unique()->implode(' / ');
            //     $grpo->PoDate = $grpo->grpoLines->pluck('purchaseOrder.DocDate')->unique()->implode(' / ');
            //     return $grpo;
            // });
            $pos = $pos->concat($posCCC);
        }
        $pos = $pos->sortBy('DocDate')->values();
        return view('poMonitoring.spi.summary', compact('suppliers', 'pos', 'companyFilter'));
    }

    public function cottIndexGraph(Request $request)
    {
        $companyFilter = $request->company ?? 'All';
        $supplierFilter = $request->supplier ?? [];

        $fromDate = Carbon::parse($request->from_month)->startOfMonth();
        $toDate   = Carbon::parse($request->to_month)->endOfMonth();

        $rows = collect();


        if ($companyFilter == 'WHI' || $companyFilter == 'All') {

            $car = OPOR::join('POR1','OPOR.DocEntry','=','POR1.DocEntry')
                ->selectRaw("
                    YEAR(OPOR.DocDate) YearNo,
                    MONTH(OPOR.DocDate) MonthNo,
                    DATENAME(MONTH,OPOR.DocDate) MonthName,
                    'CAR' Company,
                    SUM(POR1.Quantity) Quantity,
                    SUM(POR1.Quantity * POR1.Price) Amount
                ")
                ->whereBetween('OPOR.DocDate',[$fromDate,$toDate])
                ->where('POR1.ItemCode','SWDCOTPHIL')
                ->where('OPOR.CANCELED','N')
                ->when(!empty($supplierFilter), function($q) use ($supplierFilter){
                    $q->whereIn('OPOR.CardName',$supplierFilter);
                })
                ->groupBy(
                    DB::raw('YEAR(OPOR.DocDate)'),
                    DB::raw('MONTH(OPOR.DocDate)'),
                    DB::raw('DATENAME(MONTH, OPOR.DocDate)')
                )
                ->get();

            $rows = $rows->merge($car);
        }


        if ($companyFilter == 'CCC' || $companyFilter == 'All') {

            $ccc = OPOR_CCC::join('POR1','OPOR.DocEntry','=','POR1.DocEntry')
                ->selectRaw("
                    YEAR(OPOR.DocDate) YearNo,
                    MONTH(OPOR.DocDate) MonthNo,
                    DATENAME(MONTH,OPOR.DocDate) MonthName,
                    'CCC' Company,
                    SUM(POR1.Quantity) Quantity,
                    SUM(POR1.Quantity * POR1.Price) Amount
                ")
                ->whereBetween('OPOR.DocDate',[$fromDate,$toDate])
                ->where('POR1.ItemCode','Seaweeds-COTTONII')
                ->where('OPOR.CANCELED','N')
                ->when(!empty($supplierFilter), function($q) use ($supplierFilter){
                    $q->whereIn('OPOR.CardName',$supplierFilter);
                })
                ->groupBy(
                    DB::raw('YEAR(OPOR.DocDate)'),
                    DB::raw('MONTH(OPOR.DocDate)'),
                    DB::raw('DATENAME(MONTH, OPOR.DocDate)')
                )
                ->get();

            $rows = $rows->merge($ccc);
        }


        $report = [];

        foreach ($rows as $row) {

            $month = $row->MonthNo;

            if (!isset($report[$month])) {

                $report[$month] = [
                    'month' => $row->MonthName,
                    'CAR' => 0,
                    'CCC' => 0,
                    // 'PBI' => 0,
                    'TotalQty' => 0,
                    'TotalAmount' => 0,
                    'AveragePrice' => 0
                ];
            }

            $report[$month][$row->Company] = $row->Quantity;

            $report[$month]['TotalQty'] += $row->Quantity;
            $report[$month]['TotalAmount'] += $row->Amount;
        }

        foreach ($report as &$month) {

            $month['AveragePrice'] =
                $month['TotalQty'] > 0
                ? $month['TotalAmount'] / $month['TotalQty']
                : 0;
        }

        ksort($report);

        return view('poMonitoring.cott.graph',[
            'report'=>$report
        ]);
    }

    public function spiIndexGraph(Request $request)
    {
        $companyFilter = $request->company ?? 'All';
        $supplierFilter = $request->supplier ?? [];

        $fromDate = Carbon::parse($request->from_month)->startOfMonth();
        $toDate   = Carbon::parse($request->to_month)->endOfMonth();

        $rows = collect();


        if ($companyFilter == 'WHI' || $companyFilter == 'All') {

            $car = OPOR::join('POR1','OPOR.DocEntry','=','POR1.DocEntry')
                ->selectRaw("
                    YEAR(OPOR.DocDate) YearNo,
                    MONTH(OPOR.DocDate) MonthNo,
                    DATENAME(MONTH,OPOR.DocDate) MonthName,
                    'CAR' Company,
                    SUM(POR1.Quantity) Quantity,
                    SUM(POR1.Quantity * POR1.Price) Amount
                ")
                ->whereBetween('OPOR.DocDate',[$fromDate,$toDate])
                ->where('POR1.ItemCode','SWDSPIPHIL')
                ->where('OPOR.CANCELED','N')
                ->when(!empty($supplierFilter), function($q) use ($supplierFilter){
                    $q->whereIn('OPOR.CardName',$supplierFilter);
                })
                ->groupBy(
                    DB::raw('YEAR(OPOR.DocDate)'),
                    DB::raw('MONTH(OPOR.DocDate)'),
                    DB::raw('DATENAME(MONTH, OPOR.DocDate)')
                )
                ->get();

            $rows = $rows->merge($car);
        }


        if ($companyFilter == 'CCC' || $companyFilter == 'All') {

            $ccc = OPOR_CCC::join('POR1','OPOR.DocEntry','=','POR1.DocEntry')
                ->selectRaw("
                    YEAR(OPOR.DocDate) YearNo,
                    MONTH(OPOR.DocDate) MonthNo,
                    DATENAME(MONTH,OPOR.DocDate) MonthName,
                    'CCC' Company,
                    SUM(POR1.Quantity) Quantity,
                    SUM(POR1.Quantity * POR1.Price) Amount
                ")
                ->whereBetween('OPOR.DocDate',[$fromDate,$toDate])
                ->where('POR1.ItemCode','Seaweeds - SPINOSUM')
                ->where('OPOR.CANCELED','N')
                ->when(!empty($supplierFilter), function($q) use ($supplierFilter){
                    $q->whereIn('OPOR.CardName',$supplierFilter);
                })
                ->groupBy(
                    DB::raw('YEAR(OPOR.DocDate)'),
                    DB::raw('MONTH(OPOR.DocDate)'),
                    DB::raw('DATENAME(MONTH, OPOR.DocDate)')
                )
                ->get();

            $rows = $rows->merge($ccc);
        }


        $report = [];

        foreach ($rows as $row) {

            $month = $row->MonthNo;

            if (!isset($report[$month])) {

                $report[$month] = [
                    'month' => $row->MonthName,
                    'CAR' => 0,
                    'CCC' => 0,
                    // 'PBI' => 0,
                    'TotalQty' => 0,
                    'TotalAmount' => 0,
                    'AveragePrice' => 0
                ];
            }

            $report[$month][$row->Company] = $row->Quantity;

            $report[$month]['TotalQty'] += $row->Quantity;
            $report[$month]['TotalAmount'] += $row->Amount;
        }

        foreach ($report as &$month) {

            $month['AveragePrice'] =
                $month['TotalQty'] > 0
                ? $month['TotalAmount'] / $month['TotalQty']
                : 0;
        }

        ksort($report);

        return view('poMonitoring.spi.graph',[
            'report'=>$report
        ]);
    }

}
