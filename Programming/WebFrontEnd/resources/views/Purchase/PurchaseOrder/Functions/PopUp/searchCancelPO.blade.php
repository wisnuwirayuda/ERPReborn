<div id="mySearchCancelPO" class="modal fade" role="dialog" aria-labelledby="contohModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="card-title">PO Final Approve List</label>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <table>
                            <tr>
                                <td><label>Search By</label></td>
                                <td>
                                    <div class="input-group">
                                        <select class="form-control select2bs4" style="width: 100%; border-radius:0;">
                                            <option selected="selected">PO Trano</option>
                                            <option>Date</option>
                                            <option>Project Code</option>
                                            <option>Project Name</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input autocomplete="off" style="border-radius:0;" type="text" class="form-control" id="project_code_asf" onkeyup="searchAsfProjectCode()">
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive p-0" style="height: 400px;">
                                <table class="table table-head-fixed text-nowrap" id="searchAsfTable">
                                    <thead>
                                        <tr>
                                            <th>Trano</th>
                                            <th>Date</th>
                                            <th>Project ID</th>
                                            <th>Project Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no=1; @endphp
                                        @for($i = 1; $i < 20; $i++) 
                                            <tr>
                                                <td>
                                                    <span class="tag tag-success">
                                                        <p data-dismiss="modal" class="klikSearchArf" data-id="trano {{ $i }}">Trano {{$i}}</p>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="tag tag-success">
                                                        <p data-dismiss="modal" class="klikSearchArf" data-id="trano {{ $i }}">Date {{$i}}</p>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="tag tag-success">
                                                        <p data-dismiss="modal" class="klikSearchArf" data-id="projeck_id {{ $i }}">Project ID {{$i}}</p>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="tag tag-success">
                                                        <p data-dismiss="modal" class="klikSearchArf" data-id="project_name {{ $i }}">Project Name {{$i}}</p>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--|----------------------------------------------------------------------------------|
    |                            End Function My Project Code                          |
    |----------------------------------------------------------------------------------|-->
<script>
    function searchNoTrans() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("no_trans");
        filter = input.value.toUpperCase();
        table = document.getElementById("searchAsfTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    function searchAsfProjectCode() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("project_code_asf");
        filter = input.value.toUpperCase();
        table = document.getElementById("searchAsfTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[2];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    function searchAsfSiteCode() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("site_code_asf");
        filter = input.value.toUpperCase();
        table = document.getElementById("searchAsfTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[4];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>

<script>
    $(function() {
        $(".klikSearchArf").on('click', function(e) {
            e.preventDefault(); // in chase you change to a link or button
            var $this = $(this);
            var code = $this.data("id");
            var name = $this.data("name");
            $("#managerUid").val(code);
            $("#managerName").val(name);
        });
    });
</script>