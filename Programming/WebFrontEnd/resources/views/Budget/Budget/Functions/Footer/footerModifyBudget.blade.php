<!-- DISABLE SUB BUDGET CODE KETIKA BUDGET CODE BELUM DIPILIH -->
<script>
    $("#site_code_popup").prop("disabled", true);
    $("#currency_popup").prop("disabled", true);
</script>

<!-- BUDGET CODE -->
<script>
    $('#tableGetProject tbody').on('click', 'tr', function() {

        $("#myProject").modal('toggle');

        var row = $(this).closest("tr");
        var id = row.find("td:nth-child(1)").text();
        var sys_id = $('#sys_id_budget' + id).val();
        var code = row.find("td:nth-child(2)").text();
        var name = row.find("td:nth-child(3)").text();

        $("#project_id").val(sys_id);
        $("#project_code").val(code);
        $("#project_name").val(name);
        $("#site_code").prop("disabled", false);
        $("#site_code_popup").prop("disabled", false);
        $("#site_id").val("");
        $("#site_code").val("");
        $("#site_name").val("");

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var keys = 0;
        $.ajax({
            type: 'GET',
            url: '{!! route("getSite") !!}?project_code=' + sys_id,
            success: function(data) {

                var no = 1;
                var t = $('#tableGetSite').DataTable();
                t.clear();
                $.each(data, function(key, val) {
                    keys += 1;
                    t.row.add([
                        '<tbody><tr><input id="sys_id_site' + keys + '" value="' + val.Sys_ID + '" type="hidden"><td>' + no++ + '</td>',
                        '<td>' + val.Code + '</td>',
                        '<td>' + val.Name + '</td></tr></tbody>'
                    ]).draw();
                });
            }
        });
    });
</script>

<!-- SITE CODE -->
<script>
    $('#tableGetSite tbody').on('click', 'tr', function() {
        $("#currency_popup").prop("disabled", false);
        $("#mySiteCode").modal('toggle');

        var row = $(this).closest("tr");
        var id = row.find("td:nth-child(1)").text();
        var sys_id = $('#sys_id_site' + id).val();
        var code = row.find("td:nth-child(2)").text();
        var name = row.find("td:nth-child(3)").text();

        $("#site_id").val(sys_id);
        $("#site_code").val(code);
        $("#site_name").val(name);
    });
</script>

<!-- CURRENCY -->
<script>
    $('#tableGetCurrency tbody').on('click', 'tr', function () {
        $("#myCurrency").modal('toggle');

        const siteId = $('#site_id').val();
        const budgetTableBody = document.querySelector('#budgetTable tbody');
        
        while (budgetTableBody.firstChild) {
            budgetTableBody.removeChild(budgetTableBody.firstChild);
        }

        var row = $(this).closest("tr");
        var id = row.find("td:nth-child(1)").text();
        var sys_id = $('#sys_id_currency' + id).val();
        var code = row.find("td:nth-child(2)").text();
        var name = row.find("td:nth-child(3)").text();

        if (code != "USD" && code != "IDR" && code != "BAM") {
            $("#currency_id").val("");
            $("#currency_name").val("");
            $("#currency_symbol").val("");
            $("#exchange_rate").val("");
            
            Swal.fire("Error", "Please Call Accounting Staffs to Input Current Exchange Rate. Thank You.", "error");
        } else {
            if (code == "USD") {
                $("#exchange_rate").val(16000);
            } else if (code == "IDR") {
                $("#exchange_rate").val("");
            }

            $("#currency_id").val(sys_id);
            $("#currency_name").val(name);
            $("#currency_symbol").val(code);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'GET',
                url: '{!! route("getBudget") !!}?site_code=' + siteId,
                success: function(data) {
                    var no = 1;
                    var applied = 0;
                    var status = "";
                    var statusDisplay = [];
                    var statusJustifyContentCenter = [];
                    var statusDisplay2 = [];
                    var statusJustifyContentCenter2 = [];
                    var statusForm = [];
                    
                    if (data.message == "Invalid SQL Syntax") {
                        var html = 
                            '<tr>' +
                                '<td class="container-tbody-tr-budget" colspan="14" style="color: red; font-style: italic;">' + 
                                    'No Data Available' +
                                '</td>' +
                            '</tr>';

                        $('table#budgetTable tbody').append(html);
                    } else {
                        if (code == "USD") {
                            const dummy = [
                                {
                                    quantityAbsorptionRatio: 1.8,
                                    quantity: 50,
                                    productName: "Unspecified Product",
                                    quantityRemaining: 0,
                                    product_RefID: null,
                                    priceBaseCurrencyValue: 29.99,
                                    priceBaseCurrencyISOCode: "USD",
                                    balancedBudget: 499.50
                                },
                                {
                                    quantityAbsorptionRatio: 2.2,
                                    quantity: 20,
                                    productName: "Unspecified Product",
                                    quantityRemaining: 0,
                                    product_RefID: null,
                                    priceBaseCurrencyValue: 120.50,
                                    priceBaseCurrencyISOCode: "USD",
                                    balancedBudget: 410.00
                                },
                                {
                                    quantityAbsorptionRatio: 1.5,
                                    quantity: 100,
                                    productName: "Unspecified Product",
                                    quantityRemaining: 10,
                                    product_RefID: null,
                                    priceBaseCurrencyValue: 50.00,
                                    priceBaseCurrencyISOCode: "USD",
                                    balancedBudget: 1000.00
                                },
                            ];

                            $.each(dummy, function(key, val2) {
                                var used = val2.quantityAbsorptionRatio * 100;

                                if (used == "0.00" && val2.quantity == "0.00") {
                                    var applied = 0;
                                } else {
                                    var applied = Math.round(used);
                                }

                                if (applied >= 100) {
                                    var status = "disabled";
                                }

                                if (val2.productName == "Unspecified Product") {
                                    statusDisplay[key] = "flex";
                                    statusJustifyContentCenter[key] = "center";
                                    statusDisplay2[key] = "none";
                                    statusForm[key] = "disabled";
                                    balance_qty = numberFormatPHPCustom(val2.quantityRemaining, 2);
                                } else {
                                    statusDisplay[key] = "none";
                                    statusJustifyContentCenter2[key] = "center";
                                    statusDisplay2[key] = "";
                                    statusForm[key] = "";
                                    balance_qty = numberFormatPHPCustom(val2.quantityRemaining, 2);
                                }

                                var html = 
                                    '<tr>' +
                                        '<td class="container-tbody-tr-budget" style="justify-content: center; display:' + statusDisplay[key] + '";">' + 
                                            '<div class="input-group" style="max-width: 140px !important;">' + 
                                                '<input id="product_id' + key + '" style="border-radius:0;" class="form-control" name="product_id_show" readonly>' +
                                                '<div>' +
                                                    '<span style="border-radius:0;" class="input-group-text form-control">' +
                                                        '<a href="#" id="product_popup" data-toggle="modal" data-target="#myProduct" class="myProduct" onclick="KeyFunction(' + key + ')"><img src="{{ asset("AdminLTE-master/dist/img/box.png") }}" width="13" alt=""></a>' +
                                                    '</span>' +
                                                '</div>' +
                                            '</div>' +
                                        '</td>' +

                                        '<td class="container-tbody-tr-budget" style="text-align: center !important; display:' + statusDisplay2[key] + '";">' + val2.product_RefID + '</td>' +
                                        '<td class="container-tbody-tr-budget" style="text-align: left !important; width: 50px;">' + val2.productName + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + numberFormatPHPCustom(val2.quantity, 2) + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + balance_qty + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + numberFormatPHPCustom(val2.priceBaseCurrencyValue, 2) + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + val2.priceBaseCurrencyISOCode + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + numberFormatPHPCustom(1000, 2) + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + numberFormatPHPCustom(val2.quantity * val2.priceBaseCurrencyValue, 2) + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + '<div class="d-flex justify-content-center" data-toggle="tooltip" data-placement="top" title="Pesan"> <input style="border-radius:0; width: 55px !important;" class="form-control number-only" autocomplete="off" id="modify_budget_details" name="modify_budget_details"> </div>' + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + '<div class="d-flex justify-content-center"> <input style="border-radius:0; width: 100px !important;" class="form-control number-without-negative" autocomplete="off" id="price_budget_details" name="price_budget_details"> </div>' + '</td>' +
                                        '<td class="container-tbody-tr-budget" style="padding-right: 0px !important;">' + '<div class="d-flex justify-content-center"> <input style="border-radius:0; width: 100px !important;" class="form-control number-only" autocomplete="off" id="total_budget_details" name="total_budget_details" disabled> </div>' + '</td>' +
                                    '</tr>';

                                $('table#budgetTable tbody').append(html);
                            });
                        } else {
                            $.each(data, function(key, val2) {
                                var used = val2.quantityAbsorptionRatio * 100;

                                if (used == "0.00" && val2.quantity == "0.00") {
                                    var applied = 0;
                                } else {
                                    var applied = Math.round(used);
                                }

                                if (applied >= 100) {
                                    var status = "disabled";
                                }

                                if (val2.productName == "Unspecified Product") {
                                    statusDisplay[key] = "flex";
                                    statusJustifyContentCenter[key] = "center";
                                    statusDisplay2[key] = "none";
                                    statusForm[key] = "disabled";
                                    balance_qty = "-";
                                } else {
                                    statusDisplay[key] = "none";
                                    statusJustifyContentCenter2[key] = "center";
                                    statusDisplay2[key] = "";
                                    statusForm[key] = "";
                                    balance_qty = numberFormatPHPCustom(val2.quantityRemaining, 2);
                                }

                                var html = 
                                    '<tr>' +
                                        '<td class="container-tbody-tr-budget" style="justify-content: center; display:' + statusDisplay[key] + '";">' + 
                                            '<div class="input-group" style="max-width: 140px !important;">' + 
                                                '<input id="product_id' + key + '" style="border-radius:0;" class="form-control" name="product_id_show" readonly>' +
                                                '<div>' +
                                                    '<span style="border-radius:0;" class="input-group-text form-control">' +
                                                        '<a href="#" id="product_popup" data-toggle="modal" data-target="#myProduct" class="myProduct" onclick="KeyFunction(' + key + ')"><img src="{{ asset("AdminLTE-master/dist/img/box.png") }}" width="13" alt=""></a>' +
                                                    '</span>' +
                                                '</div>' +
                                            '</div>' +
                                        '</td>' +

                                        '<td class="container-tbody-tr-budget" style="text-align: center !important; display:' + statusDisplay2[key] + '";">' + val2.product_RefID + '</td>' +
                                        '<td class="container-tbody-tr-budget" style="text-align: left !important; width: 50px;">' + val2.productName + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + numberFormatPHPCustom(val2.quantity, 2) + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + balance_qty + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + numberFormatPHPCustom(val2.priceBaseCurrencyValue, 2) + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + val2.priceBaseCurrencyISOCode + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + numberFormatPHPCustom(50000, 2) + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + numberFormatPHPCustom(val2.quantity * val2.priceBaseCurrencyValue, 2) + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + '<div class="d-flex justify-content-center" data-toggle="tooltip" data-placement="top" title="Pesan"> <input style="border-radius:0; width: 55px !important;" class="form-control number-only" autocomplete="off" id="modify_budget_details" name="modify_budget_details"> </div>' + '</td>' +
                                        '<td class="container-tbody-tr-budget">' + '<div class="d-flex justify-content-center"> <input style="border-radius:0; width: 100px !important;" class="form-control number-without-negative" autocomplete="off" id="price_budget_details" name="price_budget_details"> </div>' + '</td>' +
                                        '<td class="container-tbody-tr-budget" style="padding-right: 0px !important;">' + '<div class="d-flex justify-content-center"> <input style="border-radius:0; width: 100px !important;" class="form-control number-only" autocomplete="off" id="total_budget_details" name="total_budget_details" disabled> </div>' + '</td>' +
                                    '</tr>';

                                $('table#budgetTable tbody').append(html);
                            });
                        }
                    }
                }
            });
        }
    })
</script>

<!-- FUNCTION INPUT NUMBER ONLY OR WITHOUT NEGATIVE -->
<script>
    $(document).on('input', '.number-only', function() {
        allowNumbersOnly(this);
    });

    $(document).on('input', '.number-without-negative', function() {
        allowNumbersWithoutNegative(this);
    });
</script>

<!-- FUNCTION DISABLED KLIK KETIKA BUDGET & SITE CODE TIDAK KOSONG -->
<script>
    function checkAndDisable() {
        var projectId = $('#project_id').val();
        var projectCode = $('#project_code').val();
        var projectName = $('#project_name').val();
        var siteId = $('#site_id').val();
        var siteCode = $('#site_code').val();
        var siteName = $('#site_name').val();

        if (projectId && projectCode && projectName && siteId && siteCode && siteName) {
            $('#project_code_popup').addClass('disabled').css('pointer-events', 'none');
            $('#site_code_popup').addClass('disabled').css('pointer-events', 'none');
        } else {
            $('#project_code_popup').removeClass('disabled').css('pointer-events', 'auto');
            $('#site_code_popup').removeClass('disabled').css('pointer-events', 'auto');
        }
    }

    checkAndDisable();

    $('#myProject').on('click', function() {
        checkAndDisable();
    });

    $('#mySiteCode').on('click', function() {
        checkAndDisable();
    });

    $('#project_code, #site_code').on('input change', function() {
        checkAndDisable();
    });
</script>

<!-- FUNCTION KETIKA ADDITIONAL YES OR NO -->
<script>
    function toggleCurrencyField() {
        const additionalCORadios = document.getElementsByName('additional_co');
        const currencyField = document.getElementById('currency_field');
        const currencyID = document.getElementById('currency_id');
        const currencySymbol = document.getElementById('currency_symbol');
        const currencyName = document.getElementById('currency_name');
        const valueIDRRateField = document.getElementById('exchange_rate_field');
        const valueIDRRateInput = document.getElementById('exchange_rate');
        const valueCOAdditionalField = document.getElementById('value_co_field');
        const valueCOAdditionalInput = document.getElementById('value_co');

        additionalCORadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (radio.value === 'yes' && radio.checked) {
                    currencyField.style.display = 'flex';
                    valueIDRRateField.style.display = 'flex';
                    valueCOAdditionalField.style.display = 'flex';
                } else {
                    currencyField.style.display = 'none';
                    currencyID.value = '';
                    currencySymbol.value = '';
                    currencyName.value = '';

                    valueIDRRateField.style.display = 'none';
                    valueIDRRateInput.value = '';

                    valueCOAdditionalField.style.display = 'none';
                    valueCOAdditionalInput.value = '';
                }
            });
        });
    }

    toggleCurrencyField();
</script>

<!-- FORM ADD NEW ITEM -->
<script>
    const addNewItemBtn = document.getElementById('addNewItemBtn');
    const newItemForm = document.getElementById('newItemForm');
    const newItemFormTwo = document.getElementById('newItemFormTwo');
    const newItemFormThree = document.getElementById('newItemFormThree');
    const newItemFormFour = document.getElementById('newItemFormFour');
    const buttonItemFormTwo = document.getElementById('buttonItemForm');

    function resetFormInputs() {
        document.getElementById('products_id').value = '';
        document.getElementById('products_id_show').value = '';
        document.getElementById('products_name').value = '';
        document.getElementById('qty').value = '';
        document.getElementById('price').value = '';
        document.getElementById('total_qty_price').value = '';
    }

    function hideFormAddNewItem() {
        newItemForm.style.display = 'none';
        newItemFormTwo.style.display = 'none';
        newItemFormThree.style.display = 'none';
        newItemFormFour.style.display = 'none';
        buttonItemFormTwo.style.display = 'none';
    }

    addNewItemBtn.addEventListener('click', function() {
        if (newItemForm.style.display === 'none' || newItemForm.style.display === '' && newItemFormTwo.style.display === 'none' || newItemFormTwo.style.display === '' && newItemFormThree.style.display === 'none' || newItemFormThree.style.display === '' && newItemFormFour.style.display === 'none' || newItemFormFour.style.display === '' && buttonItemFormTwo.style.display === 'none' || buttonItemFormTwo.style.display === '') {
            newItemForm.style.display = 'flex';
            newItemFormTwo.style.display = 'flex';
            newItemFormThree.style.display = 'flex';
            newItemFormFour.style.display = 'flex';
            buttonItemFormTwo.style.display = 'flex';
        } else {
            hideFormAddNewItem();
            resetFormInputs();
        }
    });
</script>

<!-- FUNCTION MENGHITUNG TOTAL SETIAP BARIS PADA BUDGET DETAILS (TABLE) -->
<script>
    function calculateTotal(row) {
        const modifyInput = row.querySelector('input[name="modify_budget_details"]');
        const priceInput = row.querySelector('input[name="price_budget_details"]');
        const totalInput = row.querySelector('input[name="total_budget_details"]');
        const qtyAvail = row.children[4].textContent.trim().replace(/,/g, '') == '-' ? 0 : parseFloat(row.children[4].textContent.trim().replace(/,/g, ''));
        const balancedBudget = row.children[7].textContent.trim().replace(/,/g, '') == '-' ? 0 : parseFloat(row.children[7].textContent.trim().replace(/,/g, ''));
        const totalBudget = row.children[8].textContent.trim().replace(/,/g, '') == '-' ? 0 : parseFloat(row.children[8].textContent.trim().replace(/,/g, ''));

        const modifyValue = parseFloat(modifyInput.value) || 0;
        const priceValue = parseFloat(priceInput.value) || 0;

        if (qtyAvail >= 0) {
            if (modifyValue > qtyAvail) {
                Swal.fire("Error", `Modify must be less than Qty Avail`, "error");
                modifyInput.value = qtyAvail;
            }    
        } else {
            if (modifyValue < qtyAvail) {
                Swal.fire("Error", `Modify must be greater than Qty Avail`, "error");
                modifyInput.value = qtyAvail;
            }
        }

        const totalValue = modifyInput.value * priceValue;

        if (totalValue < 0) {
            if (totalValue < totalBudget) {
                Swal.fire("Error", "Total must be greater than Total Budget!", "error");
            }
        } else {
            if (totalValue > totalBudget && totalValue < balancedBudget) {
                Swal.fire("Error", "Total must be greater than Balance Budget & must be less than Total Budget!", "error");
            }
        }

        totalInput.value = Math.abs(totalValue).toFixed(2);
    }

    $('#budgetTable tbody').on('blur', 'input[name="modify_budget_details"], input[name="price_budget_details"]', function () {
        const row = $(this).closest('tr')[0];
        calculateTotal(row);
    });
</script>

<script>
    document.getElementById('buttonBudgetDetails').addEventListener('click', function () {
        const budgetTable = document.getElementById('budgetTable').querySelector('tbody');
        const listBudgetTable = document.getElementById('listBudgetTable').querySelector('tbody');
        let updated = false;
        let allBudgetDetailsData = [];
        let modifiedBudgetListData = [];

        // Loop over all rows in the budgetTable
        [...budgetTable.rows].forEach((row, index) => {
            const productIdTemp = row.querySelector('input[name="product_id_show"]');
            const productId     = row.cells[1].textContent != "null" ? row.cells[1].textContent.trim() : productIdTemp.value;
            const productName   = row.cells[2].textContent.trim();
            const qtyBudget     = row.cells[3].textContent.trim();
            const qtyAvail      = row.cells[4].textContent.trim();
            const price         = row.cells[5].textContent.trim();
            const currency      = row.cells[6].textContent.trim();
            const balanceBudget = row.cells[7].textContent.trim();
            const totalBudget   = row.cells[8].textContent.trim();
            const modifyInput   = row.querySelector('input[name="modify_budget_details"]');
            const priceInput    = row.querySelector('input[name="price_budget_details"]');
            const totalInput    = row.querySelector('input[name="total_budget_details"]');

            // Store all data from "Budget Details" table in the array
            allBudgetDetailsData.push({
                productId,
                productName,
                qtyBudget,
                qtyAvail,
                price,
                currency,
                balanceBudget,
                totalBudget,
                modifyInput: numberFormatPHPCustom(modifyInput.value, 2),
                priceInput: numberFormatPHPCustom(priceInput.value, 2),
                totalInput: numberFormatPHPCustom(totalInput.value, 2)
            });

            // Validasi Product Id, Modify, Price, dan Total untuk baris ini
            if (productId && modifyInput.value && priceInput.value && totalInput.value) {
                // Cari apakah Product Id sudah ada di Modify Budget List Table
                let existingRow = [...listBudgetTable.rows].find(listRow => listRow.cells[0].textContent.trim() === productId);

                if (existingRow) {
                    // Jika Product Id sudah ada, update nilai di Modify Budget List
                    existingRow.cells[9].textContent = modifyInput.value;
                    existingRow.cells[10].textContent = priceInput.value;
                    existingRow.cells[11].textContent = totalInput.value;
                    updated = true;
                } else {
                    // Jika Product Id belum ada, duplikat baris
                    const clonedRow = row.cloneNode(true);

                    // Ubah input menjadi teks biasa sebelum append ke listBudgetTable
                    const productIdValue = productId;
                    const modifyValue = modifyInput.value;
                    const priceValue = priceInput.value;
                    const totalValue = totalInput.value;

                    // Ubah elemen input menjadi teks
                    clonedRow.cells[0].textContent = productIdValue;
                    clonedRow.cells[9].textContent = modifyValue;
                    clonedRow.cells[10].textContent = priceValue;
                    clonedRow.cells[11].textContent = totalValue;

                    // Append cloned row ke Modify Budget List
                    listBudgetTable.appendChild(clonedRow);
                    updated = true;
                }

                // Add data for the "Modify Budget List" table
                modifiedBudgetListData.push({
                    productId,
                    productName,
                    qtyBudget,
                    qtyAvail,
                    price,
                    currency,
                    balanceBudget,
                    totalBudget,
                    modifyInput: numberFormatPHPCustom(modifyInput.value, 2),
                    priceInput: numberFormatPHPCustom(priceInput.value, 2),
                    totalInput: totalInput.value
                });
            }
        });

        // Show success message if any rows were updated or duplicated
        if (updated) {
            Swal.fire("Success", "Rows updated or duplicated to Modify Budget List", "success");

            document.getElementById('budgetDetailsData').value = JSON.stringify(allBudgetDetailsData);
            document.getElementById('modifyBudgetListData').value = JSON.stringify(modifiedBudgetListData);
        } else {
            // Show error message if no rows were valid
            Swal.fire("Error", "Please fill in Product Id, Modify(+/-), Price, and Total for at least one row", "error");
        }
    });
</script>
