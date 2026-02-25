<script>
    let dataReport      = [];
    const budgetID      = document.getElementById("budget_id");
    const budgetCode    = document.getElementById("budget_code");
    const budgetName    = document.getElementById("budget_name");
    const customerID    = document.getElementById("customer_id");
    const customerCode  = document.getElementById("customer_code");
    const customerName  = document.getElementById("customer_name");
    const remDate       = document.getElementById("reimbursement_date_range");
    const printType     = document.getElementById("print_type");

    function resetForm() {
        dataReport = [];

        $("#budget_name").css('background-color', '#fff');
        $(`#budget_name`).val("");
        $(`#budget_id`).val("");
        $(`#budget_code`).val("");

        $("#customer_name").css('background-color', '#fff');
        $(`#customer_name`).val("");
        $(`#customer_id`).val("");
        $(`#customer_code`).val("");

        $("#reimbursement_date_range").css('background-color', '#fff');
        $(`#reimbursement_date_range`).val("");
    }

    function getDataReport() {
        ShowLoading();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '{!! route("Reimbursement.ReportReimbursementSummaryStore") !!}',
            data: {
                budget_id: budgetID.value,
                budget_code: budgetCode.value,
                customer_id: customerID.value,
                remDate: remDate.value
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 200 && response.data[0]) {
                    let data = response.data;
                    dataReport = JSON.stringify(data);

                    let totalIDR            = 0;
                    let totalOtherCurrency  = 0;
                    let totalEquivalentIDR  = 0;

                    data.forEach(function(row) {
                        totalIDR            += parseFloat(row.total_IDR) || 0;
                        totalOtherCurrency  += parseFloat(row.total_Other_Currency) || 0;
                        totalEquivalentIDR  += parseFloat(row.total_Equivalent_IDR) || 0;
                    });
                    
                    $('#table_summary').DataTable({
                        destroy: true,
                        data: data,
                        deferRender: true,
                        scrollCollapse: true,
                        scroller: true,
                        columns: [
                            {
                                data: null,
                                render: function (data, type, row, meta) {
                                    return (meta.row + 1);
                                }
                            },
                            {
                                data: 'reimbursementNumber',
                                defaultContent: '-'
                            },
                            {
                                data: 'date',
                                defaultContent: '-'
                            },
                            {
                                data: null,
                                render: function (data, type, row, meta) {
                                    return `${data.combinedBudgetCode} - ${data.combinedBudgetName}`;
                                }
                            },
                            {
                                data: null,
                                render: function (data, type, row, meta) {
                                    return `${data.vendorCode} - ${data.vendor}`;
                                }
                            },
                            {
                                data: null,
                                defaultContent: '-',
                                render: function (data, type, row, meta) {
                                    return currencyTotal(data.total_IDR) || '-';
                                }
                            },
                            {
                                data: null,
                                defaultContent: '-',
                                render: function (data, type, row, meta) {
                                    return currencyTotal(data.total_Other_Currency) || '-';
                                }
                            },
                            {
                                data: null,
                                defaultContent: '-',
                                render: function (data, type, row, meta) {
                                    return currencyTotal(data.total_Equivalent_IDR) || '-';
                                }
                            },
                            {
                                data: 'remarks',
                                defaultContent: '-'
                            }
                        ],
                        drawCallback: function(settings) {
                            $('#table_summary tfoot th:nth-child(2)').text(currencyTotal(totalIDR));
                            $('#table_summary tfoot th:nth-child(3)').text(currencyTotal(totalOtherCurrency));
                            $('#table_summary tfoot th:nth-child(4)').text(currencyTotal(totalEquivalentIDR));
                        }
                    });

                    $('#table_summary').css("width", "100%");
                    $('#table_container').css("display", "block");
                } else {
                    $('#table_container').hide(); 
                    $('#table_summary tbody').empty();
                    $('#table_summary tfoot').empty();
                    ErrorNotif("Error");
                }

                HideLoading();
            },
            error: function(xhr, status, error) {
                HideLoading();
                ErrorNotif("An error occurred while processing the received data. Please try again later.");
                console.log('xhr, status, error', xhr, status, error);
            }
        });
    }

    function exportDataReport() {
        ShowLoading();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '{!! route("Reimbursement.PrintExportReportReimbursementSummary") !!}',
            data: {
                dataReport,
                budgetName: budgetName.value,
                customerName: customerName.value,
                remDate: remDate.value,
                printType: printType.value
            },
            xhrFields: { 
                responseType: 'blob'
            },
            success: function(response) {
                var blob = new Blob([response], { type: response.type });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);

                if (response.type === "application/pdf") {
                    link.download = "Export Report Reimbursement Summary.pdf";
                } else {
                    link.download = "Export Report Reimbursement Summary.xlsx";
                }

                link.click();

                window.URL.revokeObjectURL(link.href);

                HideLoading();
            },
            error: function(xhr, status, error) {
                HideLoading();
                ErrorNotif("An error occurred while processing the received data. Please try again later.");
                console.log('xhr, status, error', xhr, status, error);
            }
        });
    }

    $('#tableProjects').on('click', 'tbody tr', function() {
        const sysId   = $(this).find('input[data-trigger="sys_id_project"]').val();
        const code    = $(this).find('td:nth-child(2)').text();
        const name    = $(this).find('td:nth-child(3)').text();

        $("#budget_id").val(sysId);
        $("#budget_code").val(code);
        $("#budget_name").val(`${code} - ${name}`);
        $("#budget_name").css('background-color', '#e9ecef');

        $('#myProjects').modal('hide');
    });

    $('#tableGetCustomer').on('click', 'tbody tr', function() {
        const sysId = $(this).find('input[data-trigger="sys_id_customer"]').val();
        const code  = $(this).find('td:nth-child(2)').text();
        const name  = $(this).find('td:nth-child(3)').text();

        $("#customer_id").val(sysId);
        $("#customer_code").val(code);
        $("#customer_name").val(`${code} - ${name}`);
        $("#customer_name").css('background-color', '#e9ecef');

        $('#myCustomers').modal('hide');
    });

    $(window).one('load', function() {
        getModalCustomers();

        $('#reimbursement_date_range').daterangepicker({
            autoUpdateInput: false,
            maxDate: moment(),
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#reimbursement_date_range').on('apply.daterangepicker', function(ev, picker) {
            $("#reimbursement_date_range").css('background-color', '#e9ecef');
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('#reimbursement_date_range').on('cancel.daterangepicker', function(ev, picker) {
            $("#reimbursement_date_range").css('background-color', '#fff');
            $(this).val('');
        });

        $('#reimbursement_date_range_container_icon').on('click', function () {
            $('#reimbursement_date_range').trigger('click');
        });
    });
</script>