<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\ExportExcel\Budget\ExportReportModifyBudgetDetail;
use App\Http\Controllers\ExportExcel\Budget\ExportReportModifyBudgetSummary;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
use PDO;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $varAPIWebToken = $request->session()->get('SessionLogin');

        $varData = \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGateway(
        \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
        $varAPIWebToken, 
        'transaction.read.dataList.budgeting.getBudget', 
        'latest', 
        [
        'parameter' => null,
        'SQLStatement' => [
            'pick' => null,
            'sort' => null,
            'filter' => null,
            'paging' => null
            ]
        ]
        );
        // dd($varData);
        return view('Budget.Budget.Transactions.index', ['data' => $varData['data']]);
    }

    public function ModifyBudget(Request $request) {
        $varAPIWebToken = $request->session()->get('SessionLogin');

        // dd('Testing');

        $compact = [
            'varAPIWebToken' => $varAPIWebToken
        ];

        return view('Budget.Budget.Transactions.ModifyBudget', $compact);
    }

    public function UpdateModifyBudget(Request $request) {
        try {
            $varAPIWebToken     = $request->session()->get('SessionLogin');

            $compact = [
                'varAPIWebToken'    => $varAPIWebToken,
                'files'             => json_decode($request->input('files'), true) == [] ? null : json_decode($request->input('files'), true),
                'budgetID'          => $request->budgetID,
                'budgetCode'        => $request->budgetCode,
                'budgetName'        => $request->budgetName,
                'subBudgetID'       => $request->subBudgetID,
                'subBudgetCode'     => $request->subBudgetCode,
                'subBudgetName'     => $request->subBudgetName,
                'reason'            => $request->reason,
                'additionalCO'      => $request->additionalCO,
                'currencyID'        => $request->currencyID,
                'currencySymbol'    => $request->currencySymbol,
                'currencyName'      => $request->currencyName,
                'idrRate'           => $request->valueIDRRate,
                'valueAdditionalCO' => $request->valueAdditionalCO,
                'valueDeductiveCO'  => $request->valueDeductiveCO,
                'totalAdditional'   => $request->totalAdditional,
                'totalSaving'       => $request->totalSaving,
                'dataModifyBudget'  => json_decode($request->input('dataModifyBudget'), true),
                'parsedData'        => json_decode($request->input('parsedData'), true),
                'hiddenBudgetData'  => json_decode($request->input('hiddenBudgetData'), true),
            ];
            
            // dump($compact);
            
            return view('Budget.Budget.Transactions.UpdateModifyBudget', $compact);
        } catch (\Throwable $th) {
            Log::error("Error at UpdateModifyBudget: " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function PreviewModifyBudget(Request $request) {
        try {
            $varAPIWebToken         = $request->session()->get('SessionLogin');
            $PIC                    = $request->session()->get("SessionLoginName");
            
            $hiddenBudgetData       = $request->input('hiddenBudgetData');

            // Add Budget & Sub Budget Code
            $budgetID               = $request->project_id;
            $budgetCode             = $request->project_code;
            $budgetName             = $request->project_name;
            $subBudgetID            = $request->site_id;
            $subBudgetCode          = $request->site_code;
            $subBudgetName          = $request->site_name;

            // Add Modify Budget
            $reason                 = $request->reason_modify ?? '-';
            $additionalCO           = $request->additional_co;
            $currencyID             = $request->currency_id;
            $currencySymbol         = $request->currency_symbol ?? '';
            $currencyName           = $request->currency_name ?? '-';
            $exchangeRate           = floatval($request->exchange_rate);
            $valueCO                = floatval($request->value_co);

            // File Attachment
            $files                  = $request->dataInput_Log_FileUpload_1 ?? [];

            // Budget Details (table)
            $budgetDetailsData      = $request->input('budgetDetailsData');

            // Modify Budget List (table)
            $modifyBudgetListData   = $request->input('modifyBudgetListData');

            $compact = [
                'varAPIWebToken'        => $varAPIWebToken,
                'pic'                   => $PIC,
                'budgetID'              => $budgetID,
                'budgetCode'            => $budgetCode,
                'budgetName'            => $budgetName,
                'subBudgetID'           => $subBudgetID,
                'subBudgetCode'         => $subBudgetCode,
                'subBudgetName'         => $subBudgetName,
                'reason'                => $reason,
                'additionalCO'          => $additionalCO,
                'currencyID'            => $currencyID,
                'currencySymbol'        => $currencySymbol,
                'currencyName'          => $currencyName,
                'exchangeRate'          => $exchangeRate,
                'files'                 => $files,
                'budgetDetailsData'     => json_decode($budgetDetailsData),
                'modifyBudgetListData'  => json_decode($modifyBudgetListData),
            ];

            dd($compact);

            return view('Budget.Budget.Transactions.PreviewModifyBudget', $compact);
        } catch (\Throwable $th) {
            Log::error("Error at PreviewModifyBudget: " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function ReportModifyBudgetSummary(Request $request)
    {
        $varAPIWebToken = $request->session()->get('SessionLogin');
        $isSubmitButton = $request->session()->get('isButtonReportModifyBudgetSummarySubmit');

        $dataReport = $isSubmitButton ? $request->session()->get('dataReportModifyBudgetSummary', []) : [];

        $compact = [
            'varAPIWebToken' => [],
            'dataReport' => $dataReport
        ];

        return view('Budget.Budget.Reports.ReportModifyBudgetSummary', $compact);
    }

    public function ReportModifyBudgetSummaryData($projectId, $siteId, $projectCode, $projectName)
    {
        try {
            $varAPIWebToken = Session::get('SessionLogin');

            \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGateway(
                \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
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

            $DataReportModifyBudgetSummary = json_decode(
                \App\Helpers\ZhtHelper\Cache\Helper_Redis::getValue(
                    \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
                    "ReportAdvanceSummary"
                ),
                true
            );

            $collection = collect($DataReportModifyBudgetSummary);

            if ($projectId != "") {
                $collection = $collection->where('CombinedBudget_RefID', $projectId);
            }
            if ($siteId != "") {
                $collection = $collection->where('CombinedBudgetSection_RefID', $siteId);
            }

            $collection = $collection->all();

            $dataHeaders = [
                'budget'        => $projectCode . " - " . $projectName
            ];

            // dd($collection);

            $dataDetails = [];
            $i = 0;
            $total = 0;
            $productID = 88000000003832;
            foreach ($collection as $collections) {
                $total                              += $collections['TotalAdvance'];

                $dataDetails[$i]['no']              = $i + 1;
                $dataDetails[$i]['productID']       = $productID + $i;
                $dataDetails[$i]['productName']     = $collections['remark'];
                $dataDetails[$i]['price']           = $collections['TotalAdvance'];
                $dataDetails[$i]['total']           = ($i + 1) * $collections['TotalAdvance'];
    
                // $dataDetails[$i]['ModifyNumber']        = "MB01-23000004";
                // $dataDetails[$i]['budgetCode']          = $collections['CombinedBudgetCode'];
                // $dataDetails[$i]['date']                = date('d-m-Y', strtotime($collections['DocumentDateTimeTZ']));
                // $dataDetails[$i]['total']               = number_format($collections['TotalAdvance'], 2);
                $i++;
            }

            $compact = [
                'dataHeader'            => $dataHeaders,
                'dataDetail'            => $dataDetails,
                'total'                 => number_format($total, 2),
            ];

            Session::put("isButtonReportModifyBudgetSummarySubmit", true);
            Session::put("dataReportModifyBudgetSummary", $compact);

            return $compact;
        } catch (\Throwable $th) {
            Log::error("Error at ReportModifyBudgetSummaryData: " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function ReportModifyBudgetSummaryStore(Request $request) 
    {
        try {
            $budget         = $request->budget;
            $budgetID       = $request->budget_id;
            $budgetName     = $request->budget_name;
            $subBudgetID    = $request->sub_budget_id;

            if (!$budgetID && !$subBudgetID) {
                $message = 'Budget & Sub Budget Cannot Empty';
            } else if ($budgetID && !$subBudgetID) {
                $message = 'Sub Budget Cannot Empty';
            }

            if (isset($message)) {
                Session::forget("isButtonReportModifyBudgetSummarySubmit");
                Session::forget("dataReportModifyBudgetSummary");

                return redirect()->route('Budget.ReportModifyBudgetSummary')->with('NotFound', $message);
            }

            $compact = $this->ReportModifyBudgetSummaryData($budgetID, $subBudgetID, $budget, $budgetName);

            if ($compact === null || empty($compact['dataHeader'])) {
                return redirect()->back()->with('NotFound', 'Data Not Found');
            }

            return redirect()->route('Budget.ReportModifyBudgetSummary');
        } catch (\Throwable $th) {
            Log::error("Error at ReportModifyBudgetSummaryStore: " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function PrintExportReportModifyBudgetSummary(Request $request) {
        try {
            $dataReport = Session::get("dataReportModifyBudgetSummary");

            if ($dataReport) {
                if ($request->print_type == "PDF") {
                    $pdf = PDF::loadView('Budget.Budget.Reports.ReportModifyBudgetSummary_pdf', ['dataReport' => $dataReport]);
                    $pdf->output();
                    $dom_pdf = $pdf->getDomPDF();

                    $canvas = $dom_pdf ->get_canvas();
                    $width = $canvas->get_width();
                    $height = $canvas->get_height();
                    $canvas->page_text($width - 88, $height - 35, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
                    $canvas->page_text(34, $height - 35, "Print by " . $request->session()->get("SessionLoginName"), null, 10, array(0, 0, 0));
    
                    return $pdf->download('Export Report Modify Budget Summary.pdf');
                } else {
                    return Excel::download(new ExportReportModifyBudgetSummary, 'Export Report Modify Budget Summary.xlsx');
                }
            } else {
                return redirect()->route('Budget.ReportModifyBudgetSummary')->with('NotFound', 'Budget & Sub Budget Cannot Empty');
            }
        } catch (\Throwable $th) {
            Log::error("Error at PrintExportReportModifyBudgetSummary: " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function ReportModifyBudgetDetail(Request $request)
    {
        $varAPIWebToken = $request->session()->get('SessionLogin');
        $isSubmitButton = $request->session()->get('isButtonReportModifyBudgetDetailSubmit');

        $dataReport = $isSubmitButton ? $request->session()->get('dataReportModifyBudgetDetail', []) : [];

        // dump($dataReport);

        $compact = [
            'varAPIWebToken'    => [],
            'dataReport'        => $dataReport
        ];

        return view('Budget.Budget.Reports.ReportModifyBudgetDetail', $compact);
    }

    public function ReportModifyBudgetDetailData($id) 
    {
        try {
            $varAPIWebToken = Session::get('SessionLogin');

            $filteredArray = \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGateway(
                \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
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
                throw new \Exception('Data not found in the API response.');
            }

            $getData = $filteredArray['data'][0]['document'];

            // DATA HEADER
            $dataHeaders = [
                'doNumber'      => 'MB01-53000004',
                'budget'        => $getData['content']['general']['budget']['combinedBudgetCodeList'][0],
                'budgetName'    => $getData['content']['general']['budget']['combinedBudgetNameList'][0],
                'subBudget'     => $getData['content']['general']['budget']['combinedBudgetSectionCodeList'][0],
                'date'          => $getData['header']['date'],
                'transporter'   => "VDR-2594 - Aman Jaya",
                'deliveryFrom'  => "QDC",
                'deliveryTo'    => 'Gudang Tigaraksa',
                'PIC'           => $getData['content']['general']['involvedPersons'][0]['requesterWorkerName'],
            ];

            // dd($getData['content']['details']['itemList']);

            $dataDetails = [];
            $i = 0;
            $totalQty = 0;
            foreach ($getData['content']['details']['itemList'] as $dataReports) {
                $totalQty += ($i + 1) * $dataReports['entities']['quantity'];
            
                $dataDetails[$i]['no']          = $i + 1;
                $dataDetails[$i]['productID']   = $dataReports['entities']['product_RefID'];
                $dataDetails[$i]['productName'] = $dataReports['entities']['productName'];
                $dataDetails[$i]['price']       = $dataReports['entities']['quantity'];
                $dataDetails[$i]['total']       = ($i + 1) * $dataReports['entities']['quantity'];

                // $dataDetails[$i]['dorNumber']   = "MB1-23000004";
                // $dataDetails[$i]['productId']   = $dataReports['entities']['product_RefID'];
                // $dataDetails[$i]['productName'] = $dataReports['entities']['productName'];
                // $dataDetails[$i]['qty']         = number_format($dataReports['entities']['quantity'], 2, ',', '.');
                // $dataDetails[$i]['uom']         = 'Set';
                // $dataDetails[$i]['remark']      = $dataReports['entities']['quantityUnitName'];
                $i++;
            }

            $compact = [
                'dataHeader'    => $dataHeaders,
                'dataDetail'    => $dataDetails,
                'totalQty'      => number_format($totalQty, 2, ',', '.'),
            ];

            Session::put("isButtonReportModifyBudgetDetailSubmit", true);
            Session::put("dataReportModifyBudgetDetail", $compact);

            return $compact;
        } catch (\Throwable $th) {
            Log::error("Error at ReportModifyBudgetDetailData: " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function ReportModifyBudgetDetailStore(Request $request) 
    {
        try {
            $advanceRefID   = $request->advance_RefID;
            $advanceNumber  = $request->advance_number;

            if (!$advanceRefID && !$advanceNumber) {
                Session::forget("isButtonReportModifyBudgetDetailSubmit");
                Session::forget("dataReportModifyBudgetDetail");

                return redirect()->route('Budget.ReportModifyBudgetDetail')->with('NotFound', 'Modify Number Cannot Empty');
            }

            $compact = $this->ReportModifyBudgetDetailData($advanceRefID);

            if ($compact === null || empty($compact)) {
                return redirect()->back()->with('NotFound', 'Data Not Found');
            }

            return redirect()->route('Budget.ReportModifyBudgetDetail');
        } catch (\Throwable $th) {
            Log::error("Error at ReportModifyBudgetDetailStore: " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function PrintExportReportModifyBudgetDetail(Request $request) {
        try {
            $dataReport = Session::get("dataReportModifyBudgetDetail");

            if ($dataReport) {
                if ($request->print_type == "PDF") {
                    $pdf = PDF::loadView('Budget.Budget.Reports.ReportModifyBudgetDetail_pdf', ['dataReport' => $dataReport]);
                    $pdf->output();
                    $dom_pdf = $pdf->getDomPDF();

                    $canvas = $dom_pdf ->get_canvas();
                    $width = $canvas->get_width();
                    $height = $canvas->get_height();
                    $canvas->page_text($width - 88, $height - 35, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
                    $canvas->page_text(34, $height - 35, "Print by " . $request->session()->get("SessionLoginName"), null, 10, array(0, 0, 0));
    
                    return $pdf->download('Export Report Modify Budget Detail.pdf');
                } else {
                    return Excel::download(new ExportReportModifyBudgetDetail, 'Export Report Modify Budget Detail.xlsx');
                }
            } else {
                return redirect()->route('Budget.ReportModifyBudgetDetail')->with('NotFound', 'Modify Number Cannot Empty');
            }
        } catch (\Throwable $th) {
            Log::error("Error at PrintExportReportModifyBudgetDetail: " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function create()
    {
        return view('Budget.Budget.Transactions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $start = date('Y-m-d h:m:s+07', strtotime($request->start));
        $end = date('Y-m-d h:m:s+07', strtotime($request->end));
        $varAPIWebToken = $request->session()->get('SessionLogin');

        $varData = \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGateway(
        \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
        $varAPIWebToken, 
        'transaction.create.budgeting.setBudget', 
        'latest', 
        [
        'entities' => [
            'name' => $request->name,
            'validStartDateTimeTZ' => $start,
            'validFinishDateTimeTZ' => $end
            ]
        ]
        );
        return redirect()->route('Budget.index');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $varAPIWebToken = $request->session()->get('SessionLogin');
        
        $varData = \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGateway(
        \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
        $varAPIWebToken, 
        'transaction.read.dataRecord.budgeting.getBudget', 
        'latest', 
        [
        'recordID' => (int)$id
        ]
        );
        return view('Budget.Budget.Transactions.edit')->with('data', $varData['data']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $start = date('Y-m-d h:m:s+07', strtotime($request->start));
        $end = date('Y-m-d h:m:s+07', strtotime($request->end));
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $varAPIWebToken = $request->session()->get('SessionLogin');
        //---Core---
        $varData = \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGateway(
        \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
        $varAPIWebToken, 
        'transaction.delete.budgeting.setBudget', 
        'latest', 
        [
        'recordID' => (int)$id
        ]
        );
        return redirect()->route('Budget.index');
    }
}