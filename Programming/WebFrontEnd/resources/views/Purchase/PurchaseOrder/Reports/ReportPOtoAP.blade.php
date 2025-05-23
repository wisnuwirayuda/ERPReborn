@extends('Partials.app')
@section('main')
@include('Partials.navbar')
@include('Partials.sidebar')
@include('getFunction.getProject')
@include('getFunction.getSite')
@include('getFunction.getBeneficiary')
@include('getFunction.getWorker')

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-1" style="background-color:#4B586A;">
                <div class="col-sm-6" style="height:30px;">
                    <label style="font-size:15px;position:relative;top:7px;color:white;">Report Purchase Order to Delivery Order</label>
                </div>
            </div>
            <div class="card">
                <div class="tab-content p-3" id="nav-tabContent">
                    <!-- FORM -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row p-1" style="row-gap: 1rem;">
                                        @include('Purchase.PurchaseOrder.Functions.Header.HeaderReportPOtoAP')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    <?php if ($dataReport) { ?>
                    <!-- HEADER -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row py-2 px-1" style="gap: 1rem;">
                                        <label class="p-0 text-bold mb-0">Budget</label>
                                        <div>: <?= $dataReport['budgetCode']; ?> - <?= $dataReport['budgetName']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-head-fixed text-nowrap" id="DefaultFeatures">
                                        <thead>
                                            <tr>
                                                <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">No</th>
                                                <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">PO Number</th>
                                                <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">PO Total</th>
                                                <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">Valuta</th>
                                                <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">AP Number</th>
                                                <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">AP Total</th>
                                                <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">Balance PO-AP</th>
                                                <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">Payment AP</th>
                                                <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">Payment Date</th>
                                                <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">Balance Net AP-Pay</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $counter = 1; ?>
                                            <?php foreach ($dataReport['dataDetail'] as $dataDetail) { ?>
                                                <tr>
                                                    <td><?= $counter++; ?></td>
                                                    <td><?= $dataDetail['DocumentNumber']; ?></td>
                                                    <td><?= $dataDetail['TotalAdvance']; ?></td>
                                                    <td><?= $dataDetail['CurrencyName']; ?></td>
                                                    <td><?= $dataDetail['DepartingFrom']; ?></td>
                                                    <td><?= $dataDetail['DestinationTo']; ?></td>
                                                    <td><?= $dataDetail['TotalExpenseClaimCart']; ?></td>
                                                    <td><?= $dataDetail['TotalAmountDueToCompanyCart']; ?></td>
                                                    <td><?= date('d-m-Y', strtotime($dataDetail['DocumentDateTimeTZ'])); ?></td>
                                                    <td><?= $dataDetail['Description']; ?></td>
                                                    
                                                    
                                    
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <!-- <tr>
                                                <th colspan="4" style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: left;background-color:#4B586A;color:white;">GRAND TOTAL</th>
                                                <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: left;background-color:#4B586A;color:white;"><?= number_format(0, 2, '.', ','); ?></th>
                                                <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: left;background-color:#4B586A;color:white;"><?= number_format(0, 2, '.', ','); ?></th>
                                                <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: left;background-color:#4B586A;color:white;"><?= number_format(0, 2, '.', ','); ?></th>
                                                <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;background-color:#4B586A;"></th>
                                                <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;background-color:#4B586A;"></th>
                                            </tr> -->
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }; Session::forget("isButtonReportPOSubmit"); ?> 
                </div>
            </div>
        </div>
    </section>
</div>

@include('Partials.footer')
@include('Purchase.PurchaseOrder.Functions.Footer.footerReportPOtoAP')
@endsection