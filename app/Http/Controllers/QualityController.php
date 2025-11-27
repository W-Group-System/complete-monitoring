<?php

namespace App\Http\Controllers;
use App\Appearance;
use App\AuditLog;
use App\CccQualityApprover;
use App\ChemicalTesting;
use App\Fom;
use App\OPDN;
use App\OPDN_CCC;
use App\Quality;
use App\Color;
use App\QualityApproverSetup;
use App\Sand;
use PDF;
use App\Mail\QualityApprovedMail;
use App\Mail\QualityBulkApprovedMail;
use Illuminate\Support\Facades\Mail;
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
        ->whereDate('DocDate', '>=', date('2024-11-01'))
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
    public function salesorder(Request $request)
    {
        $model = OPDN::select('DocNum')->get();
        return response()->json( $model);

    }
    public function returnedQuality(Request $request)
    {
        $search = $request->input('search');
        $model = null;

        $returnedGrpoNos = Quality::on('mysql')
        ->where('status', 'Returned')
        ->pluck('grpo_no')
        ->toArray();

        $userGrpoNos = Quality::on('mysql')
        ->where('requested_by', auth()->user()->id)
        ->where('status', 'Returned')
        ->pluck('grpo_no')
        ->toArray();

        if ($request->is('returned_quality')) {
            $model = OPDN::query();
        } elseif ($request->is('ccc_returned_quality')) {
            $model = OPDN_CCC::query();
        }
        
        if (auth()->user()->position === 'Administrator') {
            $grpos = $model->whereIn('DocNum', $returnedGrpoNos)
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
        } else {
            $grpos = $model->whereIn('DocNum', $userGrpoNos)
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
        }
        
         return view('quality.returned', compact('grpos', 'search'));
    }
    public function approvalQuality(Request $request)
    {
        $search = $request->input('search');
        $model = null;

        $approvalGrpoNos = Quality::on('mysql')
        ->where('status', 'Pending')
        ->pluck('grpo_no')
        ->toArray();

        $userGrpoNos = Quality::on('mysql')
        ->where('requested_by', auth()->user()->id)
        ->where('status', 'Pending')
        ->pluck('grpo_no')
        ->toArray();

        if ($request->is('for_approval')) {
            $model = OPDN::query();
        } elseif ($request->is('ccc_for_approval')) {
            $model = OPDN_CCC::query();
        }
        if (auth()->user()->position === 'Administrator') {
            $grpos = $model->whereIn('DocNum', $approvalGrpoNos)
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
        } else {
            $grpos = $model->whereIn('DocNum', $userGrpoNos)
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
        }
        return view('quality.approval', compact('grpos', 'search'));
    }
    public function approvedQuality(Request $request)
    {
        $search = $request->input('search');
        $model = null;

        $approvedGrpoNos = Quality::on('mysql')
        ->where('status', 'Approved')
        ->pluck('grpo_no')
        ->toArray();

        $userGrpoNos = Quality::on('mysql')
        ->where('requested_by', auth()->user()->id)
        ->where('status', 'Approved')
        ->pluck('grpo_no')
        ->toArray();

        if ($request->is('approved_quality')) {
            $model = OPDN::query();
        } elseif ($request->is('ccc_approved_quality')) {
            $model = OPDN_CCC::query();
        }

        if (auth()->user()->position === 'Administrator') {
             $grpos = $model->whereIn('DocNum', $approvedGrpoNos)
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
        } else{
             $grpos = $model->whereIn('DocNum', $userGrpoNos)
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
        }
        
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
        $quality->company = "WHI";
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

        $logs = new AuditLog();
        $logs->user_id = auth()->id();
        $logs->action = "Approved Quality Request";
        $logs->remarks = $request->remarks;
        $logs->model_id = $approveQuality->id;
        $logs->save();

        $recipientEmail = 'seaweeds@rico.com.ph'; 
        $details = OPDN::where('DocNum', $approveQuality->grpo_no)->first();
        $pdf = PDF::loadView('quality.print', ['details' => $details])
                ->setPaper('a4', 'portrait');

        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $font = $fontMetrics->getFont('Helvetica', 'normal');
            $size = 10;
            $leftX = 40;
            $y1 = 800;

            $canvas->text($leftX, $y1, 'FR-QCD-10', $font, $size);
            $canvas->text($leftX, $y1 + 12, 'Rev. 08 06/02/2025', $font, $size);

            $rightX = 460;
            $canvas->text($rightX, $y1, 'Copy to: SW Purchasing:', $font, $size);
            $canvas->text($rightX, $y1 + 12, 'Filed by: QCD-SW', $font, $size);
            $canvas->text($rightX, $y1 + 24, "Page $pageNumber of $pageCount", $font, $size);
        });

        $pdfContent = $pdf->output();
        $fileName = 'Quality_Report_' . $approveQuality->dr_rr . '.pdf';
        Mail::to($recipientEmail)
            ->cc([
                'jhannice.fababaer@rico.com.ph', 
                'angelo.genteroy@rico.com.ph', 
                'adrienne.abilgos@rico.com.ph', 
                'seaweeds.specialist@rico.com.ph', 
                'seaweeds.warehouse@rico.com.ph',
                'carmona.fsqr@rico.com.ph'])
            ->send(new QualityApprovedMail($approveQuality, $pdfContent, $fileName));

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
        $supplierFilter = $request->input('supplier');
        $companyFilter = $request->input('company', 'All');

        $pendingGrpoNos = Quality::on('mysql')
            ->where('status', 'Approved')
            ->pluck('grpo_no')
            ->toArray();

        $grpos = collect();

        if ($companyFilter === 'WHI' || $companyFilter === 'All') {
            $grposWHI = OPDN::whereIn('DocNum', $pendingGrpoNos)
                ->whereBetween('DocDate', [$fromDate, $endDate])
                ->where('CANCELED', '!=', 'Y')
                ->when($supplierFilter, fn($q) => $q->where('CardName', $supplierFilter))
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
                ->get();

            $grpos = $grpos->concat($grposWHI);
        }

        if ($companyFilter === 'CCC' || $companyFilter === 'All') {
            $grposCCC = OPDN_CCC::whereIn('DocNum', $pendingGrpoNos)
                ->whereBetween('DocDate', [$fromDate, $endDate])
                ->when($supplierFilter, fn($q) => $q->where('CardName', $supplierFilter))
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
                ->get();

            $grpos = $grpos->concat($grposCCC);
        }
        $grpos = $grpos->sortByDesc('DocDate')->values();
        //  $grpos = OPDN::whereIn('DocNum', $pendingGrpoNos)
        //     ->whereBetween('DocDate', [$fromDate, $endDate])
        //     ->when($search, function ($query) use ($search) {
        //         $terms = explode(' ', $search);
        //         foreach ($terms as $term) {
        //             $query->where(function($q) use ($term) {
        //                 $q->where('DocNum', 'LIKE', "%{$term}%")
        //                     ->orWhere('NumAtCard', 'LIKE', "%{$term}%")
        //                     ->orWhere('CardName', 'LIKE', "%{$term}%");
        //             });
        //         }
        //     })
        // ->orderBy('DocDate', 'desc')
        // ->paginate(10);
         return view('quality.quality_report', compact('grpos', 'search','fromDate','endDate', 'companyFilter'));
    }

    public function approveAll(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No IDs provided.']);
        }
        $attachments = [];
        $subjectList = [];
        foreach ($ids as $id) {
            $quality = Quality::find($id);
            if ($quality && $quality->status !== 'Approved') {
                $quality->status = 'Approved';
                $quality->approved_by = auth()->user()->id;
                $quality->save();
                
                $log = new AuditLog();
                $log->user_id = auth()->id();
                $log->action = "Approved Quality Request";
                $log->remarks = "Bulk Approval";
                $log->model_id = $quality->id;
                $log->save();

                $subjectList[] = $quality->dr_rr;

                $details = OPDN::where('DocNum', $quality->grpo_no)->first();

                $pdf = PDF::loadView('quality.print', ['details' => $details])
                        ->setPaper('a4', 'portrait');

                $dompdf = $pdf->getDomPDF();
                $canvas = $dompdf->getCanvas();

                $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
                    $font = $fontMetrics->getFont('Helvetica', 'normal');
                    $size = 10;
                    $leftX = 40;
                    $y1 = 800;

                    $canvas->text($leftX, $y1, 'FR-QCD-10', $font, $size);
                    $canvas->text($leftX, $y1 + 12, 'Rev. 08 06/02/2025', $font, $size);

                    $rightX = 460;
                    $canvas->text($rightX, $y1, 'Copy to: SW Purchasing:', $font, $size);
                    $canvas->text($rightX, $y1 + 12, 'Filed by: QCD-SW', $font, $size);
                    $canvas->text($rightX, $y1 + 24, "Page $pageNumber of $pageCount", $font, $size);
                });

                $pdfContent = $pdf->output();
                $fileName = 'Quality_Report_' . $quality->dr_rr . '.pdf';
                $attachments[] = [
                    'content' => $pdfContent,
                    'name' => $fileName,
                ];
            }
        }

        if (!empty($attachments)) {
                $subject = "Quality Reports Approved: " . implode(', ', $subjectList);
                $recipient = 'seaweeds@rico.com.ph';

                Mail::to($recipient)
                    ->cc([
                        'jhannice.fababaer@rico.com.ph', 
                        'angelo.genteroy@rico.com.ph', 
                        'adrienne.abilgos@rico.com.ph', 
                        'seaweeds.specialist@rico.com.ph', 
                        'seaweeds.warehouse@rico.com.ph',
                        'carmona.fsqr@rico.com.ph'])
                ->send(new QualityBulkApprovedMail($attachments, $subject));
        }

        return response()->json(['success' => true]);
    }
    public function cccApproveAll(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No IDs provided.']);
        }

        $attachments = [];
        $subjectList = [];
        foreach ($ids as $id) {
        $quality = Quality::find($id);

        if (!$quality) {
            continue;
        }

        $approver = CccQualityApprover::where('quality_id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'Pending')
            ->first();

        if (!$approver) {
            continue; 
        }

        $approver->status = "Approved";
        $approver->approved_at = now();
        $approver->remarks = $request->remarks; 
        $approver->save();

        if ($quality->approvers()->where('status', 'Pending')->count() == 0) {
            $quality->status = 'Approved';
            $quality->save();

            $subjectList[] = $quality->dr_rr;

            $details = OPDN_CCC::where('DocNum', $quality->grpo_no)->first();

            $pdf = PDF::loadView('quality.cccprint', ['details' => $details])
                        ->setPaper('a4', 'portrait');
            $pdfContent = $pdf->output();
            $fileName = 'Quality_Report_' . $quality->dr_rr . '.pdf';
            $attachments[] = [
                'content' => $pdfContent,
                'name' => $fileName,
            ];
        }

        $logs = new AuditLog();
        $logs->user_id = auth()->id();
        $logs->action = "Approved Quality Request (Bulk)";
        $logs->remarks = $request->remarks;
        $logs->model_id = $quality->id;
        $logs->save();
    }

        if (!empty($attachments)) {
                $subject = "Quality Reports Approved: " . implode(', ', $subjectList);
                $recipient = 'seaweeds@rico.com.ph';

                Mail::to($recipient)
                    ->cc([
                        'qca.carmen@rico.com.ph',
                        'Michelle.piloton@rico.com.ph',
                        'ccc.operation@rico.com.ph',
                        'Fher.ocay@rico.com.ph',
                        'qc.carmen@rico.com.ph'
                    ])
                ->send(new QualityBulkApprovedMail($attachments, $subject));
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
    public function cccIndex(Request $request)
    {
        $search = $request->input('search');
        $grpoNosWithStatus = Quality::on('mysql')
        ->whereNotNull('status')
        ->where('status', '!=', '')
        ->pluck('grpo_no')
        ->toArray();

        $grpos = OPDN_CCC::where('OPDN.CANCELED',  '!=','Y')
        ->whereHas('grpoLines', function ($query) {
            $query->where(function ($q) {
                $q->where('ItemCode', 'Seaweeds-COTTONII')
                ->orWhere('ItemCode', 'Seaweeds - SPINOSUM');
            });
        })
        ->whereNotIn('DocNum', $grpoNosWithStatus)
        ->whereDate('DocDate', '>=', date('2024-11-01'))
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
         return view('quality.indexccc', compact('grpos', 'search'));
    }
    function cccPrint(Request $request, $id)
    {
        $details = OPDN_CCC::where('DocNum', '=', $id)->first();

        View::share('details', $details);
        $pdf = PDF::loadView('quality.cccprint', ['details' => $details]);
        $pdf->setPaper('a4', 'portrait');
        
        $dompdf = $pdf->getDomPDF();

    
        return $pdf->stream('Quality Report.pdf');
    }

    public function ccc_quality_approval(Request $request)
    {
        $search = $request->input('search');

        $approvals = CccQualityApprover::with('quality')
        ->where('user_id', auth()->id())
        ->where('status', 'Pending')
        ->whereHas('quality', function ($q) {
            $q->where('status', '!=', 'Returned'); 
        })
        ->get()
        ->filter(function ($approver) {
            $lowestPendingLevel = CccQualityApprover::where('quality_id', $approver->quality_id)
                ->where('status', 'Pending')
                ->min('level');

            return $approver->level == $lowestPendingLevel;
        });

        $pendingGrpoNos = $approvals->pluck('quality.grpo_no')->toArray();

        $grpos = OPDN_CCC::whereIn('DocNum', $pendingGrpoNos)
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
        return view('quality.ccc_for_approval', compact('grpos', 'search'));
    }
    public function CccApproveQuality(Request $request, $id)
    {
        $quality = Quality::findOrFail($id);

        $approver = CccQualityApprover::where('quality_id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'Pending')
            ->firstOrFail();

        $approver->status = "Approved";
        $approver->approved_at = now();
        $approver->remarks = $request->remarks; 
        $approver->save();

        
         if ($quality->approvers()->where('status', 'Pending')->count() == 0) {
            $quality->status = 'Approved';
            $quality->save();

            $details = OPDN_CCC::where('DocNum', $quality->grpo_no)->first();
            $pdf = PDF::loadView('quality.cccprint', ['details' => $details])
                    ->setPaper('a4', 'portrait');

            $dompdf = $pdf->getDomPDF();

            $pdfContent = $pdf->output();
            $fileName = 'Quality_Report_' . $quality->dr_rr . '.pdf';

            $recipientEmail = 'seaweeds@rico.com.ph';
            Mail::to($recipientEmail)
                ->cc([
                    'qca.carmen@rico.com.ph',
                    'Michelle.piloton@rico.com.ph',
                    'ccc.operation@rico.com.ph',
                    'Fher.ocay@rico.com.ph',
                    'qc.carmen@rico.com.ph'
                ])
                ->send(new QualityApprovedMail($quality, $pdfContent, $fileName));
        }

        $logs = new AuditLog();
        $logs->user_id = auth()->id();
        $logs->action = "Approved Quality Request";
        $logs->remarks = $request->remarks;
        $logs->model_id = $quality->id;
        $logs->save();
        return response()->json(['message' => 'Quality Request Approved.']);
    }
    public function CccDisapproveQuality(Request $request, $id)
    {
        $quality = Quality::findOrFail($id);

        $approver = CccQualityApprover::where('quality_id', $id)
        ->where('user_id', auth()->id())
        ->where('status', 'Pending')
        ->firstOrFail();

        $approver->status = "Returned";
        $approver->approved_at = now();
        $approver->remarks = $request->approve_remarks;
        $approver->save();

        $quality->status = "Returned";
        $quality->approve_remarks = $request->approve_remarks;
        $quality->approved_by = auth()->id();
        $quality->save();

        $logs = new AuditLog();
        $logs->user_id = auth()->id();
        $logs->action = "Returned Quality Request";
        $logs->remarks = $request->approve_remarks;
        $logs->model_id = $quality->id;
        $logs->save();

        CccQualityApprover::where('quality_id', $id)->update([
            'status' => 'Pending',
            'approved_at' => null,
            'remarks' => null,
        ]);

        return response()->json(['message' => 'Quality Request Returned.']);
    }
    public function ccc_quality_edit(Request $request, $id)
    {
        $changes = [];

        $quality = Quality::firstOrNew(['grpo_no' => $id]);
        $isNew = !$quality->exists;
        $oldValues = $quality->exists ? $quality->getOriginal() : [];

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
        $quality->company = "CCC";
        $quality->requested_by = auth()->user()->id;
        $quality->save();

        if ($isNew) {
            $changes[] = "Created Quality record (GRPO: {$quality->grpo_no})";
        } else {
            foreach ($quality->getChanges() as $field => $newValue) {
                if ($field === "updated_at") continue;
                $oldValue = $oldValues[$field] ?? null;
                $changes[] = "[Quality] {$field} changed from '{$oldValue}' to '{$newValue}'";
            }
        }
        if ($request->has('condition')) {
            $color = Color::firstOrNew(['quality_id' => $quality->id]);
            $oldValues = $color->exists ? $color->getOriginal() : [];
            $color->condition = json_encode($request->condition); 
            $color->remarks = $request->remarks;
            $color->save();

            foreach ($color->getChanges() as $field => $newValue) {
                if ($field === "updated_at") continue;
                $oldValue = $oldValues[$field] ?? null;
                $changes[] = "[Color] {$field} changed from '{$oldValue}' to '{$newValue}'";
            }
        }
        

        if ($request->has('appearance_condition')) {
            $appearance = Appearance::firstOrNew(['quality_id' => $quality->id]);
            $oldValues = $appearance->exists ? $appearance->getOriginal() : [];
            $appearance->condition = json_encode($request->appearance_condition); 
            $appearance->remarks = $request->appearance_remarks;
            $appearance->save();

            foreach ($appearance->getChanges() as $field => $newValue) {
                if ($field === "updated_at") continue;
                $oldValue = $oldValues[$field] ?? null;
                $changes[] = "[Appearance] {$field} changed from '{$oldValue}' to '{$newValue}'";
            }
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
            $oldValues = $chemical_testing->exists ? $chemical_testing->getOriginal() : [];

            $chemical_testing->parameter = $param;
            $chemical_testing->specification = $spec;
            $chemical_testing->result = $results[$i] ?? null;
            $chemical_testing->remarks = $remarks[$i] ?? null;
            $chemical_testing->save();

            foreach ($chemical_testing->getChanges() as $field => $newValue) {
                if ($field === "updated_at") continue;
                $oldValue = $oldValues[$field] ?? null;
                $changes[] = "[ChemicalTesting] {$field} changed from '{$oldValue}' to '{$newValue}' (Param: {$param}, Spec: {$spec})";
            }

        }

        $foms = Fom::firstOrNew(['quality_id' => $quality->id]);
        $oldValues = $foms->exists ? $foms->getOriginal() : [];
        // $foms->foreign_matter = json_encode($request->foms);
        $foms->foreign_matter = json_encode($request->foms ?? []);
        $foms->impurities = $request->foms_impurities;
        $foms->weight = $request->foms_weight;
        $foms->percent = $request->foms_percent;
        $foms->parts_million = $request->foms_parts;
        $foms->save();
        foreach ($foms->getChanges() as $field => $newValue) {
            if ($field === "updated_at") continue;
            $oldValue = $oldValues[$field] ?? null;
            $changes[] = "[Foms] {$field} changed from '{$oldValue}' to '{$newValue}'";
        }

        $sands = Sand::firstOrNew(['quality_id' => $quality->id]);
        $oldValues = $sands->exists ? $sands->getOriginal() : [];
        $sands->foreign_matter = json_encode($request->salts ?? []);
        $sands->impurities = $request->salts_impurities;
        $sands->weight = $request->salts_weight;
        $sands->percent = $request->salts_percent;
        $sands->parts_million = $request->salts_parts;
        $sands->save();

        foreach ($sands->getChanges() as $field => $newValue) {
            if ($field === "updated_at") continue;
            $oldValue = $oldValues[$field] ?? null;
            $changes[] = "[Sand] {$field} changed from '{$oldValue}' to '{$newValue}'";
        }

        $setups = QualityApproverSetup::where('status', 'Active')->orderBy('level')->get();

        foreach ($setups as $setup) {
            $approver = CccQualityApprover::firstOrNew([
                'quality_id' => $quality->id,
                'user_id'    => $setup->user_id,
            ]);

            $approver->level = $setup->level;
            $approver->user_id = $setup->user_id;
            $approver->status = 'Pending';
            $approver->approved_at = null;
            $approver->remarks = null;
            $approver->save();
        }
        $logs = new AuditLog();
        $logs->user_id = auth()->user()->id;
        $logs->action = $isNew ? "Created Quality Record" : "Updated Quality Record";
        $logs->remarks = implode("; ", $changes);
        $logs->model_id = $quality->id;
        $logs->save();
        return back()->with('success', 'Quality Edited.');
    }
}
