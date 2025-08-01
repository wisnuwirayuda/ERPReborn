<?php

namespace App\Http\Controllers\Process\Advance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExportExcel\AdvanceRequest\ExportReportAdvanceSummaryDetail;
use App\Http\Controllers\ExportExcel\AdvanceRequest\ExportReportAdvanceSummary;
use App\Http\Controllers\ExportExcel\AdvanceToASF\ExportReportAdvanceToASF;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall;
use App\Helpers\ZhtHelper\System\Helper_Environment;
use App\Helpers\ZhtHelper\Cache\Helper_Redis;
use App\Services\Process\Advance\AdvanceRequestService;
use App\Services\WorkflowService;

class AdvanceRequestController extends Controller
{
    protected $advanceRequestService, $workflowService;

    public function __construct(AdvanceRequestService $advanceRequestService, WorkflowService $workflowService)
    {
        $this->advanceRequestService    = $advanceRequestService;
        $this->workflowService          = $workflowService;
    }

    // +--------------------------------------------------------------------------------------------------------------------------+
    // |                                        TRANSACTIONS                                                                      |
    // +--------------------------------------------------------------------------------------------------------------------------+

    // INDEX FUNCTION
    public function index(Request $request)
    {
        $varAPIWebToken = Session::get('SessionLogin');
        $var            = 0;
        if (!empty($_GET['var'])) {
            $var = $_GET['var'];
        }

        $compact = [
            'var'               => $var,
            'varAPIWebToken'    => $varAPIWebToken,
        ];

        return view('Process.Advance.AdvanceRequest.Transactions.CreateAdvanceRequest', $compact);
    }

    // STORE FUNCTION FOR INSERT DATA (NEW FUNCTION)
    public function store(Request $request)
    {
        try {
            $response = $this->advanceRequestService->create($request);

            if ($response['metadata']['HTTPStatusCode'] !== 200) {
                return response()->json($response);
            }

            $responseWorkflow = $this->workflowService->submit(
                $response['data']['businessDocument']['businessDocument_RefID'],
                $request->workFlowPath_RefID,
                $request->comment,
                $request->approverEntity,
            );

            if ($responseWorkflow['metadata']['HTTPStatusCode'] !== 200) {
                return response()->json($responseWorkflow);
            }

            $compact = [
                "documentNumber"    => $response['data']['businessDocument']['documentNumber'],
                "status"            => $responseWorkflow['metadata']['HTTPStatusCode'],
            ];

            return response()->json($compact);
        } catch (\Throwable $th) {
            Log::error("Store Advance Request Function Error: " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    // CALCULATE TOTAL
    public function calculateTotal($filteredData, $key) {
        return array_reduce($filteredData, function ($carry, $item) use ($key) {
            return $carry + ($item[$key] ?? 0);
        }, 0);
    }

    // REVISION FUNCTION FOR SHOW LIST DATA FILTER BY ID 
    public function RevisionAdvanceIndex(Request $request)
    {
        try {
            $varAPIWebToken = Session::get('SessionLogin');
            $advance_RefID  = $request->input('modal_advance_id');

            $response = $this->advanceRequestService->getDetail($advance_RefID);

            if ($response['metadata']['HTTPStatusCode'] !== 200) {
                return response()->json($response);
            }

            $dataAdvanceDetail = $response['data']['data'];

            $compact = [
                'varAPIWebToken'                => $varAPIWebToken,
                'statusRevisi'                  => 0,
                'advance_RefID'                 => $dataAdvanceDetail[0]['advance_RefID'] ?? '', 
                'headerAdvanceRevision'         => [
                    'budgetCode'                => $dataAdvanceDetail[0]['combinedBudgetCode'] ?? '',
                    'budgetCodeId'              => $dataAdvanceDetail[0]['combinedBudget_RefID'] ?? '', 
                    'budgetCodeName'            => $dataAdvanceDetail[0]['combinedBudgetName'] ?? '',
                    'subBudgetCode'             => $dataAdvanceDetail[0]['combinedBudgetSectionCode'] ?? '',
                    'subBudgetCodeId'           => $dataAdvanceDetail[0]['combinedBudgetSection_RefID'] ?? '', 
                    'subBudgetCodeName'         => $dataAdvanceDetail[0]['combinedBudgetSectionName'] ?? '',
                ],
                'headerAdvanceRequestDetail'    => [
                    'requesterPosition'         => $dataAdvanceDetail[0]['requesterWorkerJobPositionName'] ?? '', 
                    'requesterId'               => $dataAdvanceDetail[0]['requesterWorkerJobsPosition_RefID'] ?? '', 
                    'requesterName'             => $dataAdvanceDetail[0]['requesterWorkerName'] ?? '', 
                    'beneficiaryPosition'       => $dataAdvanceDetail[0]['beneficiaryWorkerJobsPositionName'] ?? '', 
                    'beneficiaryId'             => $dataAdvanceDetail[0]['beneficiaryWorkerJobsPosition_RefID'] ?? '', 
                    'beneficiaryName'           => $dataAdvanceDetail[0]['beneficiaryWorkerName'] ?? '', 
                    'person_RefId'              => $dataAdvanceDetail[0]['person_RefID'] ?? '',
                    'bankAcronym'               => $dataAdvanceDetail[0]['beneficiaryBankAcronym'] ?? '', 
                    'bankId'                    => $dataAdvanceDetail[0]['beneficiaryBank_RefID'] ?? '', 
                    'bankName'                  => $dataAdvanceDetail[0]['beneficiaryBankName'] ?? '', 
                    'bankAccountNumber'         => $dataAdvanceDetail[0]['beneficiaryBankAccountNumber'] ?? '', 
                    'bankAccountId'             => $dataAdvanceDetail[0]['beneficiaryBankAccount_RefID'] ?? '', 
                    'bankAccountName'           => $dataAdvanceDetail[0]['beneficiaryBankAccountName'] ?? '', 
                ],
                'dataAdvanceList'               => $dataAdvanceDetail,
                'fileAttachment'                => $dataAdvanceDetail[0]['log_FileUpload_Pointer_RefID'] ?? null, 
                'remark'                        => $dataAdvanceDetail[0]['remarks'] ?? '' 
            ];

            // dump($compact);

            return view('Process.Advance.AdvanceRequest.Transactions.RevisionAdvanceRequest', $compact);
        } catch (\Throwable $th) {
            Log::error("RevisionAdvanceIndex Function Error: " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    // UPDATE FUNCTION
    public function UpdatesAdvanceRequest(Request $request)
    {
        try {
            $response = $this->advanceRequestService->updates($request);

            if ($response['metadata']['HTTPStatusCode'] !== 200) {
                return response()->json($response);
            }

            $responseWorkflow = $this->workflowService->submit(
                $response['data'][0]['businessDocument']['businessDocument_RefID'],
                $request->workFlowPath_RefID,
                $request->comment,
                $request->approverEntity,
            );

            if ($responseWorkflow['metadata']['HTTPStatusCode'] !== 200) {
                return response()->json($responseWorkflow);
            }

            $compact = [
                "documentNumber"    => $response['data'][0]['businessDocument']['documentNumber'],
                "status"            => $responseWorkflow['metadata']['HTTPStatusCode'],
            ];

            return response()->json($compact);
        } catch (\Throwable $th) {
            Log::error("Updates Function Error: " . $th->getMessage());

            $compact = [
                "status" => 500
            ];

            return response()->json($compact);
        }
    }

    // LIST DATA FUNCTION FOR SHOW DATA ADVANCE 
    public function AdvanceListData(Request $request)
    {
        try {

            // if (Redis::get("DataListAdvance") == null) {
                $varAPIWebToken = Session::get('SessionLogin');
                $DataListAdvance = Helper_APICall::setCallAPIGateway(
                    Helper_Environment::getUserSessionID_System(),
                    $varAPIWebToken,
                    'transaction.read.dataList.finance.getAdvance',
                    'latest',
                    [
                        'parameter' => null,
                        'SQLStatement' => [
                            'pick' => null,
                            'sort' => null,
                            'filter' => null,
                            'paging' => null
                        ]
                    ],
                    false
                );
            // }

            // $DataListAdvance = json_decode(
            //     Helper_Redis::getValue(
            //         Helper_Environment::getUserSessionID_System(),
            //         "DataListAdvance"
            //     ),
            //     true
            // );

            $collection = collect($DataListAdvance["data"]);

            $project_id = $request->project_id;
            $site_id = $request->site_id;

            if ($project_id != "") {
                $collection = $collection->where('combinedBudget_RefID', $project_id);
            }
            if ($site_id != "") {
                $collection = $collection->where('combinedBudgetSection_RefID', $site_id);
            }

            Log::error("collection", $collection);

            $collection = $collection->all();

            return response()->json($collection);
        } catch (\Throwable $th) {
            Log::error("Error at " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    // +--------------------------------------------------------------------------------------------------------------------------+
    // |                                        REPORTS                                                                           |
    // +--------------------------------------------------------------------------------------------------------------------------+

    public function ReportAdvanceSummary(Request $request)
    {
        try {
            Session::put("AdvanceSummaryReportIsSubmit", "No");

            $varAPIWebToken = Session::get('SessionLogin');

            $compact = [
                'varAPIWebToken' => $varAPIWebToken,
                'statusRevisi' => 0,
            ];

            return view('Process.Advance.AdvanceRequest.Reports.ReportAdvanceSummary', $compact);
        } catch (\Throwable $th) {
            Log::error("Error at " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function ReportAdvanceSummaryStore(Request $request)
    {
        try {
            $varAPIWebToken = Session::get('SessionLogin');
            // if (Redis::get("ReportAdvanceSummary") == null) {
                Helper_APICall::setCallAPIGateway(
                    Helper_Environment::getUserSessionID_System(),
                    $varAPIWebToken,
                    'report.form.documentForm.finance.getReportAdvanceSummary',
                    'latest',
                    [
                        'parameter' => [
                            'dataFilter' => [
                                'budgetID' => 1,
                                'subBudgetID' => 1,
                                'workID' => 1,
                                'productID' => 1,
                                'beneficiaryID' => 1,
                            ]
                        ]
                    ],
                    false
                );
            // }

            $DataReportAdvanceSummary = json_decode(
                Helper_Redis::getValue(
                    Helper_Environment::getUserSessionID_System(),
                    "ReportAdvanceSummary"
                ),
                true
            );

            $collection = collect($DataReportAdvanceSummary);

            $project_id = $request->project_id;
            $site_id = $request->site_id;
            // $work_id = $request->work_id;
            $requester_id = $request->requester_id;
            $beneficiary_id = $request->beneficiary_id;

            if ($project_id != "") {
                $collection = $collection->where('CombinedBudget_RefID', $project_id);
            }
            if ($site_id != "") {
                $collection = $collection->where('CombinedBudgetSection_RefID', $site_id);
            }
            if ($requester_id != "") {
                $collection = $collection->where('RequesterWorkerJobsPosition_RefID', $requester_id);
            }
            if ($beneficiary_id != "") {
                $collection = $collection->where('BeneficiaryWorkerJobsPosition_RefID', $beneficiary_id);
            }
            // if ($work_id != "") {
            //     $work_id = null;
            // }

            $collection = $collection->all();

            $varDataExcel = [];
            $varDataProject = [];
            $i = 0;
            $total = 0;
            foreach ($collection as $collections) {

                $total +=  $collections['TotalAdvance'];

                $varDataProject[0]['projectCode'] = $collections['CombinedBudgetCode'];
                $varDataProject[0]['projectName'] = $collections['CombinedBudgetName'];

                $varDataExcel[$i]['no'] = $i + 1;
                $varDataExcel[$i]['documentNumber'] = $collections['DocumentNumber'];
                $varDataExcel[$i]['subBudget'] = $collections['CombinedBudgetSectionName'];
                $varDataExcel[$i]['date'] = date('d-m-Y', strtotime($collections['DocumentDateTimeTZ']));
                $varDataExcel[$i]['requester'] = $collections['RequesterWorkerName'];
                $varDataExcel[$i]['beneficiary'] = $collections['BeneficiaryWorkerName'];
                $varDataExcel[$i]['total_idr'] = number_format($collections['TotalAdvance'], 2);
                $varDataExcel[$i]['total_other'] = number_format($collections['TotalAdvance'], 2);
                $varDataExcel[$i]['total_equivalent'] = number_format($collections['TotalAdvance'], 2);
                // $varDataExcel[$i]['currency'] = $collections['CurrencyName'];
                $varDataExcel[$i]['remark'] = ucfirst(trans($collections['remark']));

                $i++;
            }

            $compact = [
                'data' => $collection,
                'varDataExcel' => $varDataExcel,
                'varDataProject' => $varDataProject
            ];

            Session::put("AdvanceSummaryReportDataPDF", $compact);
            Session::put("AdvanceSummaryReportDataExcel", $compact['varDataExcel']);
            Session::put("AdvanceSummaryReportTotal", number_format($total, 2));
            Session::put("AdvanceSummaryReportIsSubmit", "Yes");

            return response()->json($compact);
        } catch (\Throwable $th) {
            Log::error("Error at " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function PrintExportReportAdvanceSummary(Request $request)
    {
        try {

            $isSubmit = Session::get("AdvanceSummaryReportIsSubmit");
            if ($isSubmit == "Yes") {
                $print_type = $request->print_type;
                if ($print_type == "PDF") {

                    $dataAdvance = Session::get("AdvanceSummaryReportDataPDF");

                    $data = [
                        'title' => 'ADVANCE REQUEST SUMMARY',
                        'date' => date('d/m/Y H:m:s'),
                        'projectCode' => $dataAdvance['varDataProject'][0]['projectCode'],
                        'projectName' => $dataAdvance['varDataProject'][0]['projectName'],
                        'printedBy' => Session::get('SessionLoginName'),
                        'data' => $dataAdvance
                    ];

                    $pdf = PDF::loadView('Process.Advance.AdvanceRequest.Reports.PrintReportAdvanceSummary', $data);
                    $pdf->output();
                    $dom_pdf = $pdf->getDomPDF();

                    $canvas = $dom_pdf ->get_canvas();
                    $width = $canvas->get_width();
                    $height = $canvas->get_height();
                    $canvas->page_text($width - 88, $height - 35, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
                    $canvas->page_text(34, $height - 35, "Print by " . $request->session()->get("SessionLoginName"), null, 10, array(0, 0, 0));

                    return $pdf->download('Export Report Advance Summary.pdf');
                } else if ($print_type == "Excel") {

                    return Excel::download(new ExportReportAdvanceSummary, 'Export Report Advance Summary.xlsx');
                }
            } else {
                return redirect()->route('AdvanceRequest.ReportAdvanceSummary')->with('NotFound', 'Data Cannot Empty');
            }
        } catch (\Throwable $th) {
            Log::error("Error at " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function ReportAdvanceSummaryDetail(Request $request)
    {
        $varAPIWebToken = $request->session()->get('SessionLogin');
        $isSubmitButton = $request->session()->get('AdvanceSummaryReportDetailIsSubmit');

        $dataReport = $isSubmitButton ? $request->session()->get('AdvanceSummaryReportDetailDataPDF', []) : [];

        $compact = [
            'varAPIWebToken'    => $varAPIWebToken,
            'dataReport'        => $isSubmitButton ? true : false,
            "dataHeader"        => $dataReport["dataHeader"] ?? null,
            "dataContent"       => $dataReport["dataContent"] ?? null,
            "dataDetail"        => $dataReport["dataDetail"] ?? null,
            "dataExcel"         => $dataReport["dataExcel"] ?? null,
            "statusDetail"      => $dataReport["statusDetail"] ?? null,
            "advance_RefID"     => $dataReport["advance_RefID"] ?? null,
            "advance_number"    => $dataReport["advance_number"] ?? null,
            "statusHeader"      => $dataReport["statusHeader"] ?? null
        ];

        return view('Process.Advance.AdvanceRequest.Reports.ReportAdvanceSummaryDetail', $compact);
    }

    public function ReportAdvanceSummaryDetailData($id, $number, $statusHeader)
    {
        try {
            
            $varAPIWebToken = Session::get('SessionLogin');

            $filteredArray = Helper_APICall::setCallAPIGateway(
                Helper_Environment::getUserSessionID_System(),
                $varAPIWebToken, 
                'report.form.documentForm.finance.getAdvance', 
                'latest',
                [
                    'parameter' => [
                        'recordID' => (int) $id
                    ]
                ]
            );

            if ($filteredArray['metadata']['HTTPStatusCode'] !== 200) {
                return redirect()->back()->with('NotFound', 'Process Error');
            }

            $document           = $filteredArray['data'][0]['document'];
            $content            = $document['content'];
            $general            = $content['general'];
            $budget             = $general['budget'];
            $bankAccount        = $general['bankAccount']['beneficiary'];
            $involvedPersons    = $general['involvedPersons'][0];
            $itemList           = $content['details']['itemList'][0];

            $varDataExcel   = [];
            $dataHeader     = [];
            $i              = 0;
            $totalAdvance   = 0;

            foreach ($content['details']['itemList'] as $collections) {
                $totalAdvance += $collections['entities']['priceBaseCurrencyValue'];

                $varDataExcel[$i]['no']                                 = $i + 1;
                $varDataExcel[$i]['product_RefID']                      = $collections['entities']['product_RefID'];
                $varDataExcel[$i]['productName']                        = $collections['entities']['productName'];
                $varDataExcel[$i]['quantity']                           = number_format($collections['entities']['quantity'], 2);
                $varDataExcel[$i]['productUnitPriceBaseCurrencyValue']  = number_format($collections['entities']['productUnitPriceBaseCurrencyValue'], 2);
                $varDataExcel[$i]['priceBaseCurrencyValue']             = number_format($collections['entities']['priceBaseCurrencyValue'], 2);

                $dataHeader[$i]['Product_RefID']                        = $collections['entities']['product_RefID'];
                $dataHeader[$i]['ProductName']                          = $collections['entities']['productName'];
                $dataHeader[$i]['Quantity']                             = $collections['entities']['quantity'];
                $dataHeader[$i]['QuantityUnitName']                     = $collections['entities']['quantityUnitName'];
                $dataHeader[$i]['ProductUnitPriceBaseCurrencyValue']    = $collections['entities']['productUnitPriceBaseCurrencyValue'];
                $dataHeader[$i]['PriceBaseCurrencyValue']               = $collections['entities']['priceBaseCurrencyValue'];

                if ($i === 0) {
                    $dataHeader[$i]['DocumentNumber']                       = $document['header']['number'];
                    $dataHeader[$i]['Date']                                 = $document['header']['date'];
                    $dataHeader[$i]['ProductUnitPriceCurrencyISOCode']      = $itemList['entities']['priceCurrencyISOCode'];
                    $dataHeader[$i]['CombinedBudgetCode']                   = $budget['combinedBudgetCodeList'][0];
                    $dataHeader[$i]['CombinedBudgetName']                   = $budget['combinedBudgetNameList'][0];
                    $dataHeader[$i]['CombinedBudgetSectionCode']            = $budget['combinedBudgetSectionCodeList'][0];
                    $dataHeader[$i]['CombinedBudgetSectionName']            = $budget['combinedBudgetSectionNameList'][0];
                    $dataHeader[$i]['Log_FileUpload_Pointer_RefID']         = $general['attachmentFiles']['main']['log_FileUpload_Pointer_RefID'];
                    $dataHeader[$i]['RequesterWorkerName']                  = $involvedPersons['requesterWorkerName'];
                    $dataHeader[$i]['BeneficiaryWorkerName']                = $involvedPersons['beneficiaryWorkerName'];
                    $dataHeader[$i]['BankAcronym']                          = $bankAccount['bankAcronym'];
                    $dataHeader[$i]['BankAccountName']                      = $bankAccount['bankAccountName'];
                    $dataHeader[$i]['BankAccountNumber']                    = $bankAccount['bankAccountNumber'];
                }

                $i++;
            }
            
            $compact = [
                'dataHeader'    => $dataHeader,
                'dataContent'   => $general,
                'dataExcel'     => $varDataExcel,
                'statusDetail'  => 1,
                'advance_RefID' => $document['header']['recordID'],
                'advance_number'=> $document['header']['number'],
                'statusHeader'  => $statusHeader,
            ];

            // dd($filteredArray['metadata']['HTTPStatusCode'], $compact);

            Session::put("AdvanceSummaryReportDetailIsSubmit", "Yes");
            Session::put("AdvanceSummaryReportDetailDataPDF", $compact);
            Session::put("AdvanceSummaryReportDetailDataExcel", $compact['dataExcel']);
            Session::put("AdvanceSummaryReportDetailTotalAdvance", number_format($totalAdvance, 2));

            return $compact;
        } catch (\Throwable $th) {
            Log::error("Error at " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function ReportAdvanceSummaryDetailStore(Request $request)
    {
        try {
            $advance_RefID = $request->advance_RefID;
            $advance_number = $request->advance_number;

            $statusHeader = "Yes";

            if ($advance_RefID == "" && $advance_number == "") {
                Session::forget("AdvanceSummaryReportDetailDataPDF");
                Session::forget("AdvanceSummaryReportDetailDataExcel");
                
                return redirect()->route('AdvanceRequest.ReportAdvanceSummaryDetail')->with('NotFound', 'Advance Number Cannot Empty');
            }

            $compact = $this->ReportAdvanceSummaryDetailData($advance_RefID, $advance_number, $statusHeader);

            if ($compact['dataHeader'] == []) {
                Session::forget("AdvanceSummaryReportDetailDataPDF");
                Session::forget("AdvanceSummaryReportDetailDataExcel");

                return redirect()->back()->with('NotFound', 'Data Not Found');
            }

            return redirect()->route('AdvanceRequest.ReportAdvanceSummaryDetail');
        } catch (\Throwable $th) {
            Log::error("Error at " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function PrintExportReportAdvanceSummaryDetail(Request $request)
    {
        try {
            $dataPDF = Session::get("AdvanceSummaryReportDetailDataPDF");
            $dataExcel = Session::get("AdvanceSummaryReportDetailDataExcel");

            if ($dataPDF && $dataExcel) {
                $print_type = $request->print_type;
                if ($print_type == "PDF") {
                    $dataAdvance = Session::get("AdvanceSummaryReportDetailDataPDF");

                    $pdf = PDF::loadView('Process.Advance.AdvanceRequest.Reports.PrintReportAdvanceSummaryDetail', ['dataReport' => $dataAdvance]);
                    $pdf->output();
                    $dom_pdf = $pdf->getDomPDF();

                    $canvas = $dom_pdf ->get_canvas();
                    $width = $canvas->get_width();
                    $height = $canvas->get_height();
                    $canvas->page_text($width - 88, $height - 35, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
                    $canvas->page_text(34, $height - 35, "Print by " . $request->session()->get("SessionLoginName"), null, 10, array(0, 0, 0));

                    return $pdf->download('Export Report Advance Summary Detail.pdf');
                } else if ($print_type == "Excel") {
                    return Excel::download(new ExportReportAdvanceSummaryDetail, 'Export Report Advance Summary Detail.xlsx');
                }
            } else {
                return redirect()->route('AdvanceRequest.ReportAdvanceSummaryDetail')->with('NotFound', 'Data Cannot Empty');
            }
        } catch (\Throwable $th) {
            Log::error("Error at " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function ReportAdvanceToASF(Request $request)
    {
        try {
            $varAPIWebToken = Session::get('SessionLogin');
            $isSubmitButton = $request->session()->get('isButtonReportAdvanceToASFSubmit');

            $dataReport = $isSubmitButton ? $request->session()->get('dataReportAdvanceToASF', []) : [];

            $compact = [
                'varAPIWebToken'    => $varAPIWebToken,
                'dataReport'        => $dataReport
            ];

            return view('Process.Advance.AdvanceToASF.Reports.ReportAdvanceToASF', $compact);
        } catch (\Throwable $th) {
            Log::error("ReportAdvanceToASF Function Error at " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function ReportAdvanceToASFData($project, $site, $requester)
    {
        try {
            // Budget
            // 46000000000033, "XL Microcell 2007"
            // 46000000000009, "Nokia 2G CME & TI Project"

            // Sub Budget
            // 143000000000305, "Ampang Kuranji - Padang"
            // 143000000000308, "Bukit Pakis Sby Infill"

            // Requester
            // 164000000000521, "Fabrian Danang Destiyara"
            // 164000000000155, "M. Fikri Caesarandi Hasibuan"

            $dataDummy = [
                // Fabrian Danang Destiyara
                [
                    "Sys_ID" => 76000000000054,
                    "DocumentNumber" => "ARF01-24000057",
                    "DocumentDateTimeTZ" => "2024-06-10 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000521,
                    "RequesterWorkerName" => "Fabrian Danang Destiyara",
                    "TotalAdvance" => "4123980.00",
                    "TotalPayment" => "4123980.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-24000045",
                    "DocumentASFDateTimeTZ" => "2024-06-10 00:00:00+07",
                    "TotalSettlement" => "4123980.00",
                    "TotalExpenseClaim" => "3986503.00",
                    "TotalAmountCompany" => "137477.00",
                    "Description" => "Settlement BT Fikri Konsinyering QA / QC di semara",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000055,
                    "DocumentNumber" => "ARF01-24000056",
                    "DocumentDateTimeTZ" => "2024-06-12 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000521,
                    "RequesterWorkerName" => "Fabrian Danang Destiyara",
                    "TotalAdvance" => "2678195.00",
                    "TotalPayment" => "2678195.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-24000053",
                    "DocumentASFDateTimeTZ" => "2024-06-12 00:00:00+07",
                    "TotalSettlement" => "2678195.00",
                    "TotalExpenseClaim" => "2569412.00",
                    "TotalAmountCompany" => "108783.00",
                    "Description" => "Settlement DW Jaga Malam Tower T.08 dan T.09",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000056,
                    "DocumentNumber" => "ARF01-24000055",
                    "DocumentDateTimeTZ" => "2024-06-14 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000521,
                    "RequesterWorkerName" => "Fabrian Danang Destiyara",
                    "TotalAdvance" => "3457601.00",
                    "TotalPayment" => "3457601.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-24000004",
                    "DocumentASFDateTimeTZ" => "2024-06-14 00:00:00+07",
                    "TotalSettlement" => "3457601.00",
                    "TotalExpenseClaim" => "3224896.00",
                    "TotalAmountCompany" => "232705.00",
                    "Description" => "Settlement Biaya bulanan Scurity 6 orng , air bersih",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000057,
                    "DocumentNumber" => "ARF01-24000051",
                    "DocumentDateTimeTZ" => "2024-06-26 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000521,
                    "RequesterWorkerName" => "Fabrian Danang Destiyara",
                    "TotalAdvance" => "2198740.00",
                    "TotalPayment" => "2198740.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-24000005",
                    "DocumentASFDateTimeTZ" => "2024-06-26 00:00:00+07",
                    "TotalSettlement" => "2198740.00",
                    "TotalExpenseClaim" => "2073675.00",
                    "TotalAmountCompany" => "125065.00",
                    "Description" => "Settlement Servive Motor B 6987 VCM",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000058,
                    "DocumentNumber" => "ARF01-24000050",
                    "DocumentDateTimeTZ" => "2024-06-28 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000521,
                    "RequesterWorkerName" => "Fabrian Danang Destiyara",
                    "TotalAdvance" => "3134573.00",
                    "TotalPayment" => "3134573.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-24000006",
                    "DocumentASFDateTimeTZ" => "2024-06-28 00:00:00+07",
                    "TotalSettlement" => "3134573.00",
                    "TotalExpenseClaim" => "2897214.00",
                    "TotalAmountCompany" => "237359.00",
                    "Description" => "Settlement Biaya Pengiriman Cargo Pesawat Mur Baut",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000059,
                    "DocumentNumber" => "ARF01-25000048",
                    "DocumentDateTimeTZ" => "2024-07-03 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000521,
                    "RequesterWorkerName" => "Fabrian Danang Destiyara",
                    "TotalAdvance" => "4875412.00",
                    "TotalPayment" => "4875412.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-25000007",
                    "DocumentASFDateTimeTZ" => "2024-07-03 00:00:00+07",
                    "TotalSettlement" => "4875412.00",
                    "TotalExpenseClaim" => "4674312.00",
                    "TotalAmountCompany" => "201100.00",
                    "Description" => "Settlement Biaya Penyambungan Listrik Baru",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000060,
                    "DocumentNumber" => "ARF01-25000047",
                    "DocumentDateTimeTZ" => "2024-07-09 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000521,
                    "RequesterWorkerName" => "Fabrian Danang Destiyara",
                    "TotalAdvance" => "2768450.00",
                    "TotalPayment" => "2768450.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-25000022",
                    "DocumentASFDateTimeTZ" => "2024-07-09 00:00:00+07",
                    "TotalSettlement" => "2768450.00",
                    "TotalExpenseClaim" => "2589320.00",
                    "TotalAmountCompany" => "179130.00",
                    "Description" => "Settlement biaya pindah panel beton di gudang",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000061,
                    "DocumentNumber" => "ARF01-25000046",
                    "DocumentDateTimeTZ" => "2024-07-16 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000521,
                    "RequesterWorkerName" => "Fabrian Danang Destiyara",
                    "TotalAdvance" => "5689450.00",
                    "TotalPayment" => "5689450.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-25000025",
                    "DocumentASFDateTimeTZ" => "2024-07-16 00:00:00+07",
                    "TotalSettlement" => "5689450.00",
                    "TotalExpenseClaim" => "5471364.00",
                    "TotalAmountCompany" => "218086.00",
                    "Description" => "Settlement Biaya Perbaikan Motor untuk dibawa ke Pangkalan Bun",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000062,
                    "DocumentNumber" => "ARF01-25000045",
                    "DocumentDateTimeTZ" => "2024-07-05 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000521,
                    "RequesterWorkerName" => "Fabrian Danang Destiyara",
                    "TotalAdvance" => "1476312.00",
                    "TotalPayment" => "1476312.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-25000026",
                    "DocumentASFDateTimeTZ" => "2024-07-05 00:00:00+07",
                    "TotalSettlement" => "1476312.00",
                    "TotalExpenseClaim" => "1350874.00",
                    "TotalAmountCompany" => "125438.00",
                    "Description" => "Settlement Biaya Warmeking Surat Kuasa Jaminan",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000063,
                    "DocumentNumber" => "ARF01-25000044",
                    "DocumentDateTimeTZ" => "2024-07-19 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000521,
                    "RequesterWorkerName" => "Fabrian Danang Destiyara",
                    "TotalAdvance" => "6254399.00",
                    "TotalPayment" => "6254399.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-25000028",
                    "DocumentASFDateTimeTZ" => "2024-07-19 00:00:00+07",
                    "TotalSettlement" => "6254399.00",
                    "TotalExpenseClaim" => "6021648.00",
                    "TotalAmountCompany" => "232751.00",
                    "Description" => "Settlement Operasional Crane (Jakarta-Semarang)",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                // M. Fikri Caesarandi Hasibuan
                [
                    "Sys_ID" => 76000000000054,
                    "DocumentNumber" => "ARF01-24000057",
                    "DocumentDateTimeTZ" => "2024-06-10 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000155,
                    "RequesterWorkerName" => "M. Fikri Caesarandi Hasibuan",
                    "TotalAdvance" => "3245689.00",
                    "TotalPayment" => "3245689.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-24000045",
                    "DocumentASFDateTimeTZ" => "2024-06-10 00:00:00+07",
                    "TotalSettlement" => "3245689.00",
                    "TotalExpenseClaim" => "2986534.00",
                    "TotalAmountCompany" => "259155.00",
                    "Description" => "Settlement BT Fikri Konsinyering QA / QC di semara",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000055,
                    "DocumentNumber" => "ARF01-24000056",
                    "DocumentDateTimeTZ" => "2024-06-12 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000155,
                    "RequesterWorkerName" => "M. Fikri Caesarandi Hasibuan",
                    "TotalAdvance" => "1720360.00",
                    "TotalPayment" => "1720360.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-24000053",
                    "DocumentASFDateTimeTZ" => "2024-06-12 00:00:00+07",
                    "TotalSettlement" => "1720360.00",
                    "TotalExpenseClaim" => "1515682.00",
                    "TotalAmountCompany" => "204678.00",
                    "Description" => "Settlement DW Jaga Malam Tower T.08 dan T.09",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000056,
                    "DocumentNumber" => "ARF01-24000055",
                    "DocumentDateTimeTZ" => "2024-06-14 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000155,
                    "RequesterWorkerName" => "M. Fikri Caesarandi Hasibuan",
                    "TotalAdvance" => "2345756.00",
                    "TotalPayment" => "2345756.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-24000004",
                    "DocumentASFDateTimeTZ" => "2024-06-14 00:00:00+07",
                    "TotalSettlement" => "2345756.00",
                    "TotalExpenseClaim" => "2134987.00",
                    "TotalAmountCompany" => "210769.00",
                    "Description" => "Settlement Biaya bulanan Scurity 6 orng , air bersih",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000057,
                    "DocumentNumber" => "ARF01-24000051",
                    "DocumentDateTimeTZ" => "2024-06-26 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000155,
                    "RequesterWorkerName" => "M. Fikri Caesarandi Hasibuan",
                    "TotalAdvance" => "1532712.00",
                    "TotalPayment" => "1532712.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-24000005",
                    "DocumentASFDateTimeTZ" => "2024-06-26 00:00:00+07",
                    "TotalSettlement" => "1532712.00",
                    "TotalExpenseClaim" => "1401334.00",
                    "TotalAmountCompany" => "131378.00",
                    "Description" => "Settlement Servive Motor B 6987 VCM",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000058,
                    "DocumentNumber" => "ARF01-24000050",
                    "DocumentDateTimeTZ" => "2024-06-28 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000155,
                    "RequesterWorkerName" => "M. Fikri Caesarandi Hasibuan",
                    "TotalAdvance" => "2912439.00",
                    "TotalPayment" => "2912439.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-24000006",
                    "DocumentASFDateTimeTZ" => "2024-06-28 00:00:00+07",
                    "TotalSettlement" => "2912439.00",
                    "TotalExpenseClaim" => "2731290.00",
                    "TotalAmountCompany" => "181149.00",
                    "Description" => "Settlement Biaya Pengiriman Cargo Pesawat Mur Baut",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000059,
                    "DocumentNumber" => "ARF01-25000048",
                    "DocumentDateTimeTZ" => "2024-07-03 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000155,
                    "RequesterWorkerName" => "M. Fikri Caesarandi Hasibuan",
                    "TotalAdvance" => "3589710.00",
                    "TotalPayment" => "3589710.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-25000007",
                    "DocumentASFDateTimeTZ" => "2024-07-03 00:00:00+07",
                    "TotalSettlement" => "3589710.00",
                    "TotalExpenseClaim" => "3454672.00",
                    "TotalAmountCompany" => "135038.00",
                    "Description" => "Settlement Biaya Penyambungan Listrik Baru",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000060,
                    "DocumentNumber" => "ARF01-25000047",
                    "DocumentDateTimeTZ" => "2024-07-09 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000155,
                    "RequesterWorkerName" => "M. Fikri Caesarandi Hasibuan",
                    "TotalAdvance" => "1295614.00",
                    "TotalPayment" => "1295614.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-25000022",
                    "DocumentASFDateTimeTZ" => "2024-07-09 00:00:00+07",
                    "TotalSettlement" => "1295614.00",
                    "TotalExpenseClaim" => "1234125.00",
                    "TotalAmountCompany" => "61489.00",
                    "Description" => "Settlement biaya pindah panel beton di gudang",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000061,
                    "DocumentNumber" => "ARF01-25000046",
                    "DocumentDateTimeTZ" => "2024-07-16 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000155,
                    "RequesterWorkerName" => "M. Fikri Caesarandi Hasibuan",
                    "TotalAdvance" => "5618352.00",
                    "TotalPayment" => "5618352.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-25000025",
                    "DocumentASFDateTimeTZ" => "2024-07-16 00:00:00+07",
                    "TotalSettlement" => "5618352.00",
                    "TotalExpenseClaim" => "5245289.00",
                    "TotalAmountCompany" => "373063.00",
                    "Description" => "Settlement Biaya Perbaikan Motor untuk dibawa ke Pangkalan Bun",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000062,
                    "DocumentNumber" => "ARF01-25000045",
                    "DocumentDateTimeTZ" => "2024-07-05 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000155,
                    "RequesterWorkerName" => "M. Fikri Caesarandi Hasibuan",
                    "TotalAdvance" => "850237.00",
                    "TotalPayment" => "850237.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-25000026",
                    "DocumentASFDateTimeTZ" => "2024-07-05 00:00:00+07",
                    "TotalSettlement" => "850237.00",
                    "TotalExpenseClaim" => "812568.00",
                    "TotalAmountCompany" => "37669.00",
                    "Description" => "Settlement Biaya Warmeking Surat Kuasa Jaminan",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
                [
                    "Sys_ID" => 76000000000063,
                    "DocumentNumber" => "ARF01-25000044",
                    "DocumentDateTimeTZ" => "2024-07-19 00:00:00+07",
                    "CombinedBudgetCode" => "Q000062",
                    "CombinedBudgetName" => "XL Microcell 2007",
                    "CombinedBudgetSectionCode" => "254",
                    "CombinedBudgetSectionName" => "Ampang Kuranji - Padang",
                    "RequesterWorkerJobsPosition_RefID" => 164000000000155,
                    "RequesterWorkerName" => "M. Fikri Caesarandi Hasibuan",
                    "TotalAdvance" => "7014213.00",
                    "TotalPayment" => "7014213.00",
                    "Status" => "Final Approval",
                    "DocumentASFNumber" => "ASF01-25000028",
                    "DocumentASFDateTimeTZ" => "2024-07-19 00:00:00+07",
                    "TotalSettlement" => "7014213.00",
                    "TotalExpenseClaim" => "6810928.00",
                    "TotalAmountCompany" => "203285.00",
                    "Description" => "Settlement Operasional Crane (Jakarta-Semarang)",
                    "StatusASF" => "Final Approval",
                    "TotalAdvancePayment" => "0.00",
                    "TotalAdvanceSettlement" => "0.00",
                    "CombinedBudget_RefID" => 46000000000033,
                    "CombinedBudgetSection_RefID" => 143000000000305,
                ],
            ];

            $filteredData = array_filter($dataDummy, function ($item) use ($project, $site, $requester) {
                return 
                    (empty($project['id'])      || $item['CombinedBudget_RefID'] == $project['id']) &&
                    (empty($site['id'])         || $item['CombinedBudgetSection_RefID'] == $site['id']) &&
                    (empty($requester['id'])    || $item['RequesterWorkerJobsPosition_RefID'] == $requester['id']);
            });

            $compact = [
                'project'                   => $project,
                'site'                      => $site,
                'requester'                 => $requester,
                'dataDetail'                => $filteredData,
                'totalAdvance'              => $this->calculateTotal($filteredData, 'TotalAdvance'),
                'totalPayment'              => $this->calculateTotal($filteredData, 'TotalPayment'),
                'totalSettlement'           => $this->calculateTotal($filteredData, 'TotalSettlement'),
                'totalExpenseClaim'         => $this->calculateTotal($filteredData, 'TotalExpenseClaim'),
                'totalAmountCompany'        => $this->calculateTotal($filteredData, 'TotalAmountCompany'),
                'totalAdvancePayment'       => $this->calculateTotal($filteredData, 'TotalAdvancePayment'),
                'totalAdvanceSettlement'    => $this->calculateTotal($filteredData, 'TotalAdvanceSettlement'),
            ];

            Session::put("isButtonReportAdvanceToASFSubmit", true);
            Session::put("dataReportAdvanceToASF", $compact);

            return $compact;
        } catch (\Throwable $th) {
            Log::error("ReportAdvanceToASFData Function Error at " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function ReportAdvanceToASFStore(Request $request)
    {
        try {
            $project = [
                'id'        => $request->project_id_second,
                'code'      => $request->project_code_second,
                'name'      => $request->project_name_second,
            ];

            $site = [
                'id'        => $request->site_id_second,
                'code'      => $request->site_code_second,
                'name'      => $request->site_name_second,
            ];

            $requester = [
                'id'        => $request->worker_id_second,
                'name'      => $request->worker_name_second,
                'position'  => $request->worker_position_second,
            ];

            if (!$project['id'] && !$site['id'] && !$requester['id']) {
                Session::forget("isButtonReportAdvanceToASFSubmit");
                Session::forget("dataReportAdvanceToASF");

                return redirect()->route('AdvanceRequest.ReportAdvanceToASF')->with('NotFound', 'Budget, Sub Budget, & Requester Cannot Be Empty');
            }

            $compact = $this->ReportAdvanceToASFData($project, $site, $requester);

            if ($compact === null || empty($compact)) {
                return redirect()->back()->with('NotFound', 'Data Not Found');
            }

            return redirect()->route('AdvanceRequest.ReportAdvanceToASF');
        } catch (\Throwable $th) {
            Log::error("ReportAdvanceToASFStore Function Error at " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function PrintExportReportAdvanceToASF(Request $request)
    {
        try {
            $dataReport = Session::get("dataReportAdvanceToASF");
            $print_type = $request->print_type;
            $project_code_second_trigger = $request->project_code_second_trigger;

            if ($project_code_second_trigger == null) {
                Session::forget("isButtonReportAdvanceToASFSubmit");
                Session::forget("dataReportAdvanceToASF");

                return redirect()->route('AdvanceRequest.ReportAdvanceToASF')->with('NotFound', 'Budget, Sub Budget, & Requester Cannot Be Empty');
            }

            if ($dataReport) {
                if ($print_type === "PDF") {
                    $pdf = PDF::loadView('Process.Advance.AdvanceToASF.Reports.ReportAdvanceToASF_pdf', ['dataReport' => $dataReport])->setPaper('a4', 'landscape');
                    $pdf->output();
                    $dom_pdf = $pdf->getDomPDF();

                    $canvas = $dom_pdf ->get_canvas();
                    $width = $canvas->get_width();
                    $height = $canvas->get_height();
                    $canvas->page_text($width - 88, $height - 35, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
                    $canvas->page_text(34, $height - 35, "Print by " . $request->session()->get("SessionLoginName"), null, 10, array(0, 0, 0));

                    return $pdf->download('Export Advance To ASF.pdf');
                } else {
                    return Excel::download(new ExportReportAdvanceToASF, 'Export Advance To ASF.xlsx');
                }
            } else {
                return redirect()->route('AdvanceRequest.ReportAdvanceToASF')->with('NotFound', 'Budget, Sub Budget, & Requester Cannot Be Empty');
            }

        } catch (\Throwable $th) {
            Log::error("PrintExportReportAdvanceToASF Function Error at " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function ReportAdvanceSummaryDetailID(Request $request, $id)
    {
        try {

            Session::put("AdvanceSummaryReportDetailIsSubmit", "Yes");
            $advance_RefID = $id;
            $advance_number = "";
            $statusHeader = "No";

            $compact = $this->ReportAdvanceSummaryDetailData($advance_RefID, $advance_number, $statusHeader);

            return view('Process.Advance.AdvanceRequest.Reports.ReportAdvanceSummaryDetail', $compact);
        } catch (\Throwable $th) {
            Log::error("Error at " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }
}
