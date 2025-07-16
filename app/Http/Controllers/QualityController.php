<?php

namespace App\Http\Controllers;
use App\Appearance;
use App\ChemicalTesting;
use App\Fom;
use App\OPDN;
use App\Quality;
use App\Color;
use App\Sand;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class QualityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
         $grpoNosWithStatus = Quality::on('mysql')
        ->whereNotNull('status')
        ->where('status', '!=', '')
        ->pluck('grpo_no')
        ->toArray();

        $grpos = OPDN::where('OPDN.CANCELED',  '!=','Y')
        ->whereHas('grpoLines', function ($query) {
            $query->where(function ($q) {
                $q->where('ItemCode', 'SWDCOTPHIL')
                ->orWhere('ItemCode', 'SWDSPIPHIL');
            });
        })
        ->whereNotIn('DocNum', $grpoNosWithStatus)
        ->when($search, function ($query) use ($search, $request){
            $terms = explode(' ',$search);
            foreach ($terms as $term) {
                $query->where(function($q) use ($term, $request){
                    $q->where('DocNum', 'LIKE', "%{$term}%")
                    ->orWhere('NumAtCard', 'LIKE', "%{$term}%")
                    ->orWhere('CardName', 'LIKE', "%{$term}%");
                });
            }
        })
        ->orderBy('DocDate', 'desc')
        ->paginate(10);
         return view('quality.index', compact('grpos', 'search'));
    }
    public function returnedQuality(Request $request)
    {
        $search = $request->input('search');

        $returnedGrpoNos = Quality::on('mysql')
        ->where('status', 'Returned')
        ->pluck('grpo_no')
        ->toArray();

         $grpos = OPDN::whereIn('DocNum', $returnedGrpoNos)
            ->when($search, function ($query) use ($search) {
                $terms = explode(' ', $search);
                foreach ($terms as $term) {
                    $query->where(function($q) use ($term) {
                        $q->where('DocNum', 'LIKE', "%{$term}%")
                            ->orWhere('NumAtCard', 'LIKE', "%{$term}%")
                            ->orWhere('CardName', 'LIKE', "%{$term}%");
                    });
                }
            })
        ->orderBy('DocDate', 'desc')
        ->paginate(10);
         return view('quality.returned', compact('grpos', 'search'));
    }
    public function approvalQuality(Request $request)
    {
        $search = $request->input('search');

        $approvalGrpoNos = Quality::on('mysql')
        ->where('status', 'Approved')
        ->pluck('grpo_no')
        ->toArray();

         $grpos = OPDN::whereIn('DocNum', $approvalGrpoNos)
            ->when($search, function ($query) use ($search) {
                $terms = explode(' ', $search);
                foreach ($terms as $term) {
                    $query->where(function($q) use ($term) {
                        $q->where('DocNum', 'LIKE', "%{$term}%")
                            ->orWhere('NumAtCard', 'LIKE', "%{$term}%")
                            ->orWhere('CardName', 'LIKE', "%{$term}%");
                    });
                }
            })
        ->orderBy('DocDate', 'desc')
        ->paginate(10);
         return view('quality.approval', compact('grpos', 'search'));
    }
    public function approvedQuality(Request $request)
    {
        $search = $request->input('search');

        $approvedGrpoNos = Quality::on('mysql')
        ->where('status', 'Approved')
        ->pluck('grpo_no')
        ->toArray();

         $grpos = OPDN::whereIn('DocNum', $approvedGrpoNos)
            ->when($search, function ($query) use ($search) {
                $terms = explode(' ', $search);
                foreach ($terms as $term) {
                    $query->where(function($q) use ($term) {
                        $q->where('DocNum', 'LIKE', "%{$term}%")
                            ->orWhere('NumAtCard', 'LIKE', "%{$term}%")
                            ->orWhere('CardName', 'LIKE', "%{$term}%");
                    });
                }
            })
        ->orderBy('DocDate', 'desc')
        ->paginate(10);
         return view('quality.approved', compact('grpos', 'search'));
    }
    public function quality_edit(Request $request, $id)
    {
        $quality = Quality::firstOrNew(['grpo_no' => $id]);
        $quality->dr_rr = $request->dr_rr;
        $quality->location_bin = $request->location_bin;
        $quality->seaweeds = $request->seaweeds;
        $quality->ocular_mc = $request->ocular_mc;
        $quality->haghag = $request->haghag;
        $quality->agreed_mc = $request->agreed_mc;
        $quality->remarks = $request->quality_tab_remarks;
        $quality->budget_yield = $request->budget_yield;
        $quality->ice = $request->ice_ice;
        $quality->moss = $request->moss;
        $quality->status = "Pending";
        $quality->requested_by = auth()->user()->id;
        $quality->save();

        if ($request->has('condition')) {
            $color = Color::firstOrNew(['quality_id' => $quality->id]);
            $color->condition = json_encode($request->condition); 
            $color->remarks = $request->remarks;
            $color->save();
        }

        if ($request->has('appearance_condition')) {
            $appearance = Appearance::firstOrNew(['quality_id' => $quality->id]);
            $appearance->condition = json_encode($request->appearance_condition); 
            $appearance->remarks = $request->appearance_remarks;
            $appearance->save();
        }

        $parameters = $request->quality_parameter;
        $specifications = $request->quality_specification;
        $results = $request->quality_result;
        $remarks = $request->quality_remarks;

        for ($i = 0; $i < count($specifications); $i++) {
            $param = $parameters[$i];
            $spec = $specifications[$i];

            $chemical_testing = ChemicalTesting::firstOrNew([
                'quality_id' => $quality->id,
                'parameter' => $param, 
                'specification' => $spec,
            ]);

            $chemical_testing->parameter = $param;
            $chemical_testing->specification = $spec;
            $chemical_testing->result = $results[$i] ?? null;
            $chemical_testing->remarks = $remarks[$i] ?? null;
            $chemical_testing->save();

        }

        $foms = Fom::firstOrNew(['quality_id' => $quality->id]);
        $foms->foreign_matter = $request->foms;
        $foms->impurities = $request->foms_impurities;
        $foms->weight = $request->foms_weight;
        $foms->percent = $request->foms_percent;
        $foms->parts_million = $request->foms_parts;
        $foms->save();

        $sands = Sand::firstOrNew(['quality_id' => $quality->id]);
        $sands->foreign_matter = $request->salts;
        $sands->impurities = $request->salts_impurities;
        $sands->weight = $request->salts_weight;
        $sands->percent = $request->salts_percent;
        $sands->parts_million = $request->salts_parts;
        $sands->save();

        return back()->with('success', 'Quality Edited.');
    }

    public function quality_approval(Request $request)
    {
        $search = $request->input('search');

        $pendingGrpoNos = Quality::on('mysql')
        ->where('status', 'Pending')
        ->pluck('grpo_no')
        ->toArray();

         $grpos = OPDN::whereIn('DocNum', $pendingGrpoNos)
            ->when($search, function ($query) use ($search) {
                $terms = explode(' ', $search);
                foreach ($terms as $term) {
                    $query->where(function($q) use ($term) {
                        $q->where('DocNum', 'LIKE', "%{$term}%")
                            ->orWhere('NumAtCard', 'LIKE', "%{$term}%")
                            ->orWhere('CardName', 'LIKE', "%{$term}%");
                    });
                }
            })
        ->orderBy('DocDate', 'desc')
        ->paginate(10);
         return view('quality.for_approval', compact('grpos', 'search'));
    }
    public function ApproveQuality(Request $request, $id)
    {
        $approveQuality = Quality::findOrFail($id);
        $approveQuality->status = "Approved";
        $approveQuality->approved_by = auth()->user()->id;
        $approveQuality->save();
        return response()->json(['message' => 'Quality Request Approved.']);
    }
    public function DisapproveQuality(Request $request, $id)
    {
        $approveQuality = Quality::findOrFail($id);
        $approveQuality->status = "Returned";
        $approveQuality->approve_remarks = $request->input('approve_remarks'); 
        $approveQuality->approved_by = auth()->user()->id;
        $approveQuality->save();
        return response()->json(['message' => 'Quality Request Returned.']);
    }
    public function qualityReport(Request $request)
    {
        $search = $request->input('search');
        $fromDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $pendingGrpoNos = Quality::on('mysql')
        ->where('status', 'Approved')
        ->pluck('grpo_no')
        ->toArray();

         $grpos = OPDN::whereIn('DocNum', $pendingGrpoNos)
            ->whereBetween('DocDate', [$fromDate, $endDate])
            ->when($search, function ($query) use ($search) {
                $terms = explode(' ', $search);
                foreach ($terms as $term) {
                    $query->where(function($q) use ($term) {
                        $q->where('DocNum', 'LIKE', "%{$term}%")
                            ->orWhere('NumAtCard', 'LIKE', "%{$term}%")
                            ->orWhere('CardName', 'LIKE', "%{$term}%");
                    });
                }
            })
        ->orderBy('DocDate', 'desc')
        ->paginate(10);
         return view('quality.quality_report', compact('grpos', 'search'));
    }

    public function approveAll(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No IDs provided.']);
        }

        foreach ($ids as $id) {
            $quality = Quality::find($id);
            if ($quality && $quality->status !== 'Approved') {
                $quality->status = 'Approved';
                $quality->approved_by = auth()->user()->id;
                $quality->save();
            }
        }

        return response()->json(['success' => true]);
    }
    // public function quality_edit (Request $request, $id)
    // {
    //     $new_quality = new Quality;
    //     $new_quality->grpo_no = $id;
    //     $new_quality->dr_rr = $request->dr_rr;
    //     $new_quality->location_bin = $request->location_bin;
    //     $new_quality->seaweeds = $request->seaweeds;
    //     $new_quality->ocular_mc = $request->ocular_mc;
    //     $new_quality->haghag = $request->haghag;
    //     $new_quality->agreed_mc = $request->agreed_mc;
    //     $new_quality->remarks = $request->quality_tab_remarks;
    //     $new_quality->budget_yield = $request->budget_yield;
    //     $new_quality->ice = $request->ice_ice;
    //     $new_quality->moss = $request->moss;
    //     $new_quality->save();

    //     if ($request->has('condition')) {
    //         $color = new Color;
    //         $color->quality_id = $new_quality->id;
    //         $color->condition = json_encode($request->condition); 
    //         $color->remarks = $request->remarks;
    //         $color->save();
    //     }
    //     if ($request->has('appearance_condition')) {
    //         $appearance = new Appearance;
    //         $appearance->quality_id = $new_quality->id;
    //         $appearance->condition = json_encode($request->appearance_condition); 
    //         $appearance->remarks = $request->appearance_remarks;
    //         $appearance->save();
    //     }  

    //     $parameters = $request->quality_parameter;
    //     $specifications = $request->quality_specification;
    //     $results = $request->quality_result;
    //     $remarks = $request->quality_remarks;

    //     for ($i = 0; $i < count($parameters); $i++) {
    //          $chemical_testing = new ChemicalTesting;
    //          $chemical_testing->quality_id = $new_quality->id;
    //          $chemical_testing->parameter = $parameters[$i];
    //          $chemical_testing->specification = $specifications[$i];
    //          $chemical_testing->result =$results[$i] ?? null;
    //          $chemical_testing->remarks =$remarks[$i] ?? null;
    //          $chemical_testing->save();

    //     }

    //     $foms = new Fom();
    //     $foms->quality_id = $new_quality->id;
    //     $foms->foreign_matter = $request->foms;
    //     $foms->impurities = $request->foms_impurities;
    //     $foms->weight = $request->foms_weight;
    //     $foms->percent = $request->foms_percent;
    //     $foms->parts_million = $request->foms_parts;
    //     $foms->save();

    //     $sands = new Sand();
    //     $sands->quality_id = $new_quality->id;
    //     $sands->foreign_matter = $request->salts;
    //     $sands->impurities = $request->salts_impurities;
    //     $sands->weight = $request->salts_weight;
    //     $sands->percent = $request->salts_percent;
    //     $sands->parts_million = $request->salts_parts;
    //     $sands->save();
    // }

    function print(Request $request, $id)
    {
        $details = OPDN::where('DocNum', '=', $id)->first();

        View::share('details', $details);
        $pdf = PDF::loadView('quality.print', ['details' => $details]);
        $pdf->setPaper('a4', 'portrait');
        
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();

        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        $font = $fontMetrics->getFont('Helvetica', 'normal');
        $size = 10;

        $leftX = 40;
        $y1 = 800;
        $canvas->text($leftX, $y1, 'FR-QCD-10', $font, $size);
        $canvas->text($leftX, $y1 + 12, 'Rev. 08 06/02/2025', $font, $size);

        $rightText1 = 'Copy to: SW Purchasing:';
        $rightText2 = 'Filed by: QCD-SW';
        $rightText3 = "Page $pageNumber of $pageCount";

        $rightX = 460;
        $canvas->text($rightX, $y1, $rightText1, $font, $size);
        $canvas->text($rightX, $y1 + 12, $rightText2, $font, $size);
        $canvas->text($rightX, $y1 + 24, $rightText3, $font, $size);
    });
        return $pdf->stream('Quality Report');
    }
}
