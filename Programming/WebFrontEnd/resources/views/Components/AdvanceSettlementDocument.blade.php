<!-- LEFT COLUMN -->
<div class="col-12 col-md-6 col-lg-7">
    <div class="form-group">
        <!-- ASF NUMBER -->
        <div class="row" style="margin-bottom: 1rem;">
            <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                ASF Number
            </div>
            <div class="col">
                : <?= $dataHeader['advanceNumber']; ?>
            </div>
        </div>

        <!-- BUDGET CODE -->
        <div class="row" style="margin-bottom: 1rem;">
            <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                Budget Code
            </div>
            <div class="col">
                : <?= isset($dataHeader['budgetCode']) && isset($dataHeader['budgetName']) ? $dataHeader['budgetCode'] . ' - ' . $dataHeader['budgetName'] : '-'; ?>
            </div>
        </div>

        <!-- SUB BUDGET CODE -->
        <div class="row">
            <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                Sub Budget Code
            </div>
            <div class="col">
                : <?= isset($dataHeader['subBudgetCode']) && isset($dataHeader['subBudgetName']) ? $dataHeader['subBudgetCode'] . ' - ' . $dataHeader['subBudgetName'] : '-'; ?>
            </div>
        </div>

        <?php if (!isset($dataHeaderTransactionHistory)) { ?>
            <!-- FILE ATTACHMENT -->
            <div class="row" style="margin-top: 1rem;">
                <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                    File Attachment
                </div>
                <div class="col d-flex" style="gap: .2rem;">
                    <div>
                        :
                    </div>
                    <?php if ($dataHeader['fileID']) { ?>
                        <input type="text" id="dataInput_Log_FileUpload_1" name="dataInput_Log_FileUpload_1" style="display:none">
                        <?php echo \App\Helpers\ZhtHelper\General\Helper_JavaScript::getSyntaxCreateDOM_DivCustom_InputFile(
                        \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
                        $varAPIWebToken,
                        'dataInput_Log_FileUpload',
                        $dataHeader['fileID']
                        ).
                        ''; ?>
                    <?php } else { ?>
                        <div>-</div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<!-- RIGHT COLUMN -->
<div class="col-12 col-md-5 col-lg-4">
    <div class="form-group">
        <!-- REVISION -->
        {{-- <div class="row" id="revisionAdvance" style="margin-bottom: 1rem;">
            <div class="col-4 text-bold">
                Revision
            </div>
            <?php if (isset($dataHeader['dateUpdate'])) { ?>
                <div class="col d-flex" style="gap: .1rem;">
                    <div>
                        :
                    </div>
                    <div class="input-group">
                        <form method="POST" action="{{ route('LogTransaction') }}">
                            @csrf
                            <input type="hidden" name="id" value="<?php $dataHeader['advanceSettlement_RefID']; ?>" />
                            <input type="hidden" name="docNum" value="<?php $dataHeader['advanceNumber']; ?>" />
                            <input type="hidden" name="docName" value="<?php $transactionForm; ?>" />
                            <input type="hidden" name="page" value="<?php $page; ?>" />
                            <button type="submit" class="btn btn-default btn-sm">
                                Show Revision History
                            </button>
                        </form>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col">
                    : -
                </div>
            <?php } ?>
        </div> --}}

        <?php if (!isset($dataHeaderTransactionHistory)) { ?>
            <!-- BENEFICIARY -->
            <div class="row" style="margin-bottom: 1rem;">
                <div class="col-4 text-bold">
                    Beneficiary
                </div>
                <div class="col">
                    : <?= $dataHeader['beneficiaryName']; ?>
                </div>
            </div>

            <!-- BANK -->
            <div class="row" style="margin-bottom: 1rem;">
                <div class="col-4 text-bold">
                    Bank
                </div>
                <div class="col">
                    : (<?= $dataHeader['bankName']; ?>) <?= $dataHeader['bankAccount']; ?> - <?= $dataHeader['beneficiaryName']; ?>
                </div>
            </div>
        <?php } else { ?>
            <!-- FILE ATTACHMENT -->
            <div class="row">
                <div class="col-4 col-sm-4 col-md-3 col-lg-4 text-bold">
                    File Attachment
                </div>
                <div class="col d-flex" style="gap: .2rem;">
                    <div>
                        :
                    </div>
                    <?php if ($dataHeader['fileID']) { ?>
                        <input type="text" id="dataInput_Log_FileUpload_1" name="dataInput_Log_FileUpload_1" style="display:none">
                        <?php echo \App\Helpers\ZhtHelper\General\Helper_JavaScript::getSyntaxCreateDOM_DivCustom_InputFile(
                        \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
                        $varAPIWebToken,
                        'dataInput_Log_FileUpload',
                        $dataHeader['fileID']
                        ).
                        ''; ?>
                    <?php } else { ?>
                        <div>-</div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    let currentLocation = window.location.href;

    if (currentLocation.includes("ReportAdvanceSummaryDetail")) {
        $('#revisionAdvance').hide();
    }
</script>