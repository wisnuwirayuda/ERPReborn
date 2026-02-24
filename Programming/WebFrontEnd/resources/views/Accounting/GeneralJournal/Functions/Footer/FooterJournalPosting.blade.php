<script>
    let journalPostingDetails = [];

    function initiateJournalPosting() {
        journalPostingDetails = [];
        addRowJournalPosting();
    }

    function addRowJournalPosting(value) {
        if (value) {
            $("#journal_type").prop("disabled", true);
        }

        const newRow = {
            transaction_number_posting: '',
            transaction_id_posting: '',
            budget_code_posting: '',
            budget_id_posting: '',
            supplier_code_posting: '',
            supplier_id_posting: ''
        };

        journalPostingDetails.push(newRow);

        onClickGeneralJournalButton();
        renderTableJournalPosting();
    }

    function removeRowJournalPosting(index) {
        journalPostingDetails.splice(index, 1);
        renderTableJournalPosting();
    }

    function updateJournalPostingField(index, field, value) {
        journalPostingDetails[index][field] = value;
    }

    function renderTableJournalPosting() {
        const tbody = document.getElementById("journal_posting_body_table");
        tbody.innerHTML = "";

        journalPostingDetails.forEach((row, index) => {
            const tr = document.createElement("tr");

            if (index === journalPostingDetails.length - 1) {
                tr.innerHTML = `
                    <td style="text-align: center; padding-left: 4px !important;">
                        <div class="d-flex justify-content-center">
                            <!-- ICON PLUS -->
                            <div class="icon-plus d-flex align-items-center justify-content-center" 
                                style="width:20px;height:20px;border-radius:100%;background-color:#4B586A;margin:2px;cursor:pointer;
                                display:${index === journalPostingDetails.length - 1 ? 'flex' : 'none !important'};"
                                onclick="addRowJournalPosting('clicked')">
                                <i class="fas fa-plus" style="color:#fff;"></i>
                            </div>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                `;
            } else {
                tr.innerHTML = `
                    <td style="text-align: center; padding-left: 4px !important;">
                        <div class="d-flex justify-content-center">
                            <!-- ICON MINUS -->
                            <div class="icon-minus d-flex align-items-center justify-content-center" 
                                style="width:20px;height:20px;border-radius:100%;background-color:red;margin:2px;cursor:pointer;display:flex;"
                                onclick="{removeRowJournalPosting(${index});}">
                                <i class="fas fa-minus" style="color:#fff;"></i>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text form-control" data-toggle="modal" data-target="#myAccountPayables" onclick="pickAccountPayable(${index})" style="cursor:pointer;">
                                    <i class="fas fa-gift"></i>
                                </span>
                            </div>
                            <input type="text" id="transaction_number_posting${index}" class="form-control" readonly value="${row.transaction_number_posting}">
                            <input type="hidden" id="transaction_id_posting${index}" class="form-control" value="${row.transaction_id_posting}">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" id="budget_code_posting${index}" class="form-control" readonly value="${row.budget_code_posting}">
                            <input type="hidden" id="budget_id_posting${index}" class="form-control" value="${row.budget_id_posting}">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" id="supplier_code_posting${index}" class="form-control" readonly value="${row.supplier_code_posting}">
                            <input type="hidden" id="supplier_id_posting${index}" class="form-control" value="${row.supplier_id_posting}">
                        </div>
                    </td>
                `;
            }

            tbody.appendChild(tr);
        });
    }
</script>