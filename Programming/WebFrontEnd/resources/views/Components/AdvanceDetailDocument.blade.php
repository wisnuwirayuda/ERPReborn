<!-- LEFT COLUMN -->
<div class="col-12 col-md-6 col-lg-7">
    <div class="form-group">
        <!-- ADVANCE FORM -->
        <div class="row" style="margin-bottom: 1rem;">
            <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                Advance Form
            </div>
            <div class="col">
                : <?= $dataHeader[0]['DocumentNumber'] ?? '-'; ?>
            </div>
        </div>

        <!-- DATE -->
        <div class="row" style="margin-bottom: 1rem;">
            <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                Date
            </div>
            <div class="col">
                : <?= isset($dataHeader[0]['Date']) ? date('Y-m-d', strtotime($dataHeader[0]['Date'])) : '-'; ?>
            </div>
        </div>

        <!-- CURRENCY -->
        <div class="row" style="margin-bottom: 1rem;">
            <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                Currency
            </div>
            <div class="col">
                : <?= $dataHeader[0]['ProductUnitPriceCurrencyISOCode'] ?? $dataHeader[0]['productUnitPriceCurrencyISOCode']; ?>
            </div>
        </div>

        <!-- BUDGET CODE -->
        <div class="row" style="margin-bottom: 1rem;">
            <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                Budget Code
            </div>
            <div class="col">
                <?php $CombinedBudgetCode = isset($dataHeader[0]['CombinedBudgetCode']) ? $dataHeader[0]['CombinedBudgetCode'] : $dataHeader[0]['combinedBudgetCode']; ?>
                <?php $CombinedBudgetName = isset($dataHeader[0]['CombinedBudgetName']) ? $dataHeader[0]['CombinedBudgetName'] : $dataHeader[0]['combinedBudgetName']; ?>
                : <?= $CombinedBudgetCode . ' - ' . $CombinedBudgetName ?>
            </div>
        </div>

        <!-- SUB BUDGET CODE -->
        <div class="row" style="margin-bottom: 1rem;">
            <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                Sub Budget Code
            </div>
            <div class="col">
                <?php $CombinedBudgetSectionCode = isset($dataHeader[0]['CombinedBudgetSectionCode']) ? $dataHeader[0]['CombinedBudgetSectionCode'] : $dataHeader[0]['combinedBudgetSectionCode']; ?>
                <?php $CombinedBudgetSectionName = isset($dataHeader[0]['CombinedBudgetSectionName']) ? $dataHeader[0]['CombinedBudgetSectionName'] : $dataHeader[0]['combinedBudgetSectionName']; ?>
                : <?= $CombinedBudgetSectionCode . ' - ' . $CombinedBudgetSectionName; ?>
            </div>
        </div>

        <!-- FILE ATTACHMENT -->
        <div class="row">
            <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                File Attachment
            </div>
            <div class="col d-flex" style="gap: .2rem;">
                <div>
                    :
                </div>
                <?php if (isset($dataHeader[0]['Log_FileUpload_Pointer_RefID']) && $dataHeader[0]['Log_FileUpload_Pointer_RefID']) { ?>
                    <input type="text" id="dataInput_Log_FileUpload_1" name="dataInput_Log_FileUpload_1" style="display:none">
                    <?php echo \App\Helpers\ZhtHelper\General\Helper_JavaScript::getSyntaxCreateDOM_DivCustom_InputFile(
                    \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
                    $varAPIWebToken,
                    'dataInput_Log_FileUpload',
                    $dataHeader[0]['Log_FileUpload_Pointer_RefID']
                    ).
                    ''; ?>
                <?php } else { ?>
                    <div>-</div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- RIGHT COLUMN -->
<div class="col-12 col-md-5 col-lg-4">
    <div class="form-group">
        <!-- REVISION -->
        <div class="row" id="revisionAdvance" style="margin-bottom: 1rem;">
            <div class="col-4 text-bold">
                Revision
            </div>
            <?php if (isset($dataHeader[0]['DateUpdate'])) { ?>
                <div class="col d-flex" style="gap: .1rem;">
                    <div>
                        :
                    </div>
                    <div class="input-group">
                        <button class="btn btn-default btn-sm" onclick="window.location.href='{{ route('LogTransaction', [
                            'id'        => $dataHeader[0]['Sys_ID_Advance'],
                            'docNum'    => $dataHeader[0]['DocumentNumber'],
                            'docName'   => $dataHeader[0]['BusinessDocumentType_Name']
                            ]) }}'">
                            Show Revision History
                        </button>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col">
                    : 0
                </div>
            <?php } ?>
        </div>

        <!-- REQUESTER -->
        <div class="row" style="margin-bottom: 1rem;">
            <div class="col-4 text-bold">
                Requester
            </div>
            <div class="col">
                : <?= $dataHeader[0]['RequesterWorkerName'] ?? '-'; ?>
            </div>
        </div>

        <!-- BENEFICIARY -->
        <div class="row" style="margin-bottom: 1rem;">
            <div class="col-4 text-bold">
                Beneficiary
            </div>
            <div class="col">
                : <?= $dataHeader[0]['BeneficiaryWorkerName'] ?? '-'; ?>
            </div>
        </div>

        <!-- BANK NAME -->
        <div class="row" style="margin-bottom: 1rem;">
            <div class="col-4 text-bold">
                Bank Name
            </div>
            <div class="col">
                : <?= $dataHeader[0]['BankAcronym'] ?? '-'; ?>
            </div>
        </div>

        <!-- ACCOUNT NAME -->
        <div class="row" style="margin-bottom: 1rem;">
            <div class="col-4 text-bold">
                Account Name
            </div>
            <div class="col">
                : <?= $dataHeader[0]['BankAccountName'] ?? '-'; ?>
            </div>
        </div>

        <!-- ACCOUNT NUMBER -->
        <div class="row">
            <div class="col-4 text-bold">
                Account Number
            </div>
            <div class="col">
                : <?= $dataHeader[0]['BankAccountNumber'] ?? '-'; ?>
            </div>
        </div>
    </div>
</div>

<script>
    let currentLocation = window.location.href;

    if (currentLocation.includes("ReportAdvanceSummaryDetail")) {
        $('#revisionAdvance').hide();
    }
</script>