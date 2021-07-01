<div class="card-body">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <table>
                    <tr>
                        <td><label>Origin Of Budget</label></td>
                        <td>
                            <div class="input-group">
                                <select class="form-control" style="width: 100%;" name="origin_budget" id="origin_budget">
                                    <option selected="selected" value="">Select Budget</option>
                                    <option>Project</option>
                                    <option>Overhead</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div id="iconBudget" style="color: red;margin-left:5px;"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <table>
                    <tr>
                        <td><label>Project Code</label></td>
                        <td>
                            <div class="input-group">
                                <input id="projectcode" style="border-radius:0;" name="project_code" class="form-control" readonly required>
                                <div class="input-group-append">
                                    <span style="border-radius:0;" class="input-group-text form-control">
                                        <a href="#"><i id="projectcode2" data-toggle="modal" data-target="#myProject" class="fas fa-gift" style="color:grey;"></i></a>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input id="projectname" style="border-radius:0;" class="form-control" name="projek_name" readonly>
                        </td>
                        <td>
                            <div id="iconProject" style="color: red;margin-left:5px;"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <table>
                    <tr>
                        <td><label>Site Code</label></td>
                        <td>
                            <div class="input-group">
                                <input id="sitecode" style="border-radius:0;" name="sitecode" class="form-control" readonly required>
                                <div class="input-group-append">
                                    <span style="border-radius:0;" class="input-group-text form-control">
                                        <a href="#"><i id="sitecode2" data-toggle="modal" data-target="#mySiteCode" class="fas fa-gift" style="color:grey;"></i></a>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input id="sitename" style="border-radius:0;" class="form-control" name="site_name" readonly>
                        </td>
                        <td>
                            <div id="iconSite" style="color: red;margin-left:5px;"></div>
                        </td>
                    </tr>
                </table>
                <div class="card-body table-responsive p-0" style="height: 100px;width:100%;">
                    <table class="table table-head-fixed text-nowrap">
                        <div class="form-group input_fields_wrap">
                            <div class="input-group control-group" style="width:100%;">
                                <input type="file" class="form-control filenames_1" id="filenames_1" style="height:26px;" name="filenames">
                                <div class="input-group-btn">
                                    <a class="btn btn-outline btn-success btn-sm add_field_button">
                                        <i class="fas fa-plus" aria-hidden="true" title="Add File" style="color:white;">Add</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>