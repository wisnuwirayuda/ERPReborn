<div class="card-body table-responsive p-0" style="height: 250px;" id="tableShowHideGetBudgetArf">
    <table class="table table-head-fixed text-nowrap table-striped">
        <thead>
            <tr>
                <th>Add</th>
                <th>Applied</th>
                <th>Work ID</th>
                <th>Work Name</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Value</th>
                <th>Total Budget</th>
                <th>Total Cost</th>
                <th>Available</th>
                <!-- <th>Status</th> -->
            </tr>
        </thead>
        <tbody>
            
            @php 
                $totalApplied1 = round((((2-1) * 1000000) / 2000000 * 100),2);
                $status1 = "Miscellaneous";
            @endphp
            <td>
                @if($totalApplied1 == 100)
                <!-- <a class="btn btn-outline-primary btn-sm float-right klikBudgetArf1" title="Submit" style="border-radius: 100px;color:blue;" disabled>
                    <i class="fas fa-file" aria-hidden="true"></i>
                </a> -->
                <button type="reset" class="btn btn-outline-primary btn-sm float-right klikBudgetArf1" title="Submit" style="border-radius: 100px;" disabled>
                    <i class="fas fa-file" aria-hidden="true"></i>
                </button>
                @else
                <a class="btn btn-outline-success btn-sm float-right klikBudgetArf1" title="Submit" style="border-radius: 100px;color:green;">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                </a>
                @endif
            </td>
            <td>
                <div class="progress progress-xs" style="height: 14px;border-radius:8px;">
                    @if($totalApplied1 >= 0 && $totalApplied1 <= 40)
                    <div class="progress-bar bg-green" style="width:{{$totalApplied1}}%;"></div>
                    @elseif($totalApplied1 >= 41 && $totalApplied1 <= 89)
                    <div class="progress-bar bg-yellow" style="width:{{$totalApplied1}}%;"></div>
                    @elseif($totalApplied1 >= 90 && $totalApplied1 <= 100)
                    <div class="progress-bar bg-red" style="width:{{$totalApplied1}}%;"></div>
                    @endif
                </div>
                <small>
                    <center>{{$totalApplied1}} %</center>
                </small>
            </td>
            <td><span class="tag tag-success" id="getWorkId1">6001</span></td>
            <td><span class="tag tag-success" id="getWorkName1">Project Overhead Actual</span></td>
            <td><span class="tag tag-success" id="getProductId1">810002-0000</span></td>
            <td><span class="tag tag-success" id="getProductName1">Stationary dan Printing</span></td>
            <td><span class="tag tag-success" id="getQty11">1</span></td>
            <td><span class="tag tag-success" id="getPrice1">@currency(1000000)</span></td>
            <td><span class="tag tag-success" id="totalArf1">@currency(2000000)</span></td>
            <td><span class="tag tag-success" id="getCurrency1">IDR</span></td>
            <!-- <td><span class="tag tag-success" id="getStatus1">{{$status1}}</span></td> -->
            </tr>
            <tr>
                @php
                    $totalApplied2 = round((((4-2) * 20000000) / 80000000 * 100),2);
                    $status2 = "";
                @endphp
                <td>
                @if($totalApplied2 == 100)
                    <a class="btn btn-outline-primary btn-sm float-right klikBudgetArf2" title="Submit" style="border-radius: 100px;color:blue;" disabled>
                        <i class="fas fa-file" aria-hidden="true"></i>
                    </a>
                    @else
                    <a class="btn btn-outline-success btn-sm float-right klikBudgetArf2" title="Submit" style="border-radius: 100px;color:green;">
                        <i class="fas fa-plus" aria-hidden="true"></i>
                    </a>
                    @endif
                </td>
                <td>
                    <div class="progress progress-xs" style="height: 14px;border-radius:8px;">
                        @if($totalApplied2 >= 0 && $totalApplied2 <= 40)
                        <div class="progress-bar bg-green" style="width:{{$totalApplied2}}%;"></div>
                        @elseif($totalApplied2 >= 41 && $totalApplied2 <= 89)
                        <div class="progress-bar bg-yellow" style="width:{{$totalApplied2}}%;"></div>
                        @elseif($totalApplied2 >= 90 && $totalApplied2 <= 100)
                        <div class="progress-bar bg-red" style="width:{{$totalApplied2}}%;"></div>
                        @endif
                    </div>
                    <small>
                        <center>{{$totalApplied2}} %</center>
                    </small>
                </td>
                <td><span class="tag tag-success" id="getWorkId2">6002</span></td>
                <td><span class="tag tag-success" id="getWorkName2">Project Overhead Actual</span></td>
                <td><span class="tag tag-success" id="getProductId2">820001-0000</span></td>
                <td><span class="tag tag-success" id="getProductName2">Salaries</span></td>
                <td><span class="tag tag-success" id="getQty22">2</span></td>
                <td><span class="tag tag-success" id="getPrice2">@currency(20000000)</span></td>
                <td><span class="tag tag-success" id="totalArf2">@currency(80000000)</span></td>
                <td><span class="tag tag-success" id="getCurrency2">IDR</span></td>
                <!-- <td><span class="tag tag-success" id="getStatus2">{{$status2}}</span></td> -->
            </tr>
            <tr>
                @php
                    $totalApplied3 = round((((6-2) * 10000000) / 60000000 * 100),2);
                    $status3 = "";
                @endphp
                <td>
                @if($totalApplied3 == 100)
                    <a class="btn btn-outline-primary btn-sm float-right klikBudgetArf3" title="Submit" style="border-radius: 100px;color:blue;" disabled>
                        <i class="fas fa-file" aria-hidden="true"></i>
                    </a>
                    @else
                    <a class="btn btn-outline-success btn-sm float-right klikBudgetArf3" title="Submit" style="border-radius: 100px;color:green;">
                        <i class="fas fa-plus" aria-hidden="true"></i>
                    </a>
                    @endif
                </td>
                <td>
                    <div class="progress progress-xs" style="height: 14px;border-radius:8px;">
                        @if($totalApplied3 >= 0 && $totalApplied3 <= 40)
                        <div class="progress-bar bg-green" style="width:{{$totalApplied3}}%;"></div>
                        @elseif($totalApplied3 >= 41 && $totalApplied3 <= 89)
                        <div class="progress-bar bg-yellow" style="width:{{$totalApplied3}}%;"></div>
                        @elseif($totalApplied3 >= 90 && $totalApplied3 <= 100)
                        <div class="progress-bar bg-red" style="width:{{$totalApplied3}}%;"></div>
                        @endif
                    </div>
                    <small>
                        <center>{{$totalApplied3}} %</center>
                    </small>
                </td>
                <td><span class="tag tag-success" id="getWorkId3">6003</span></td>
                <td><span class="tag tag-success" id="getWorkName3">Project Overhead Actual</span></td>
                <td><span class="tag tag-success" id="getProductId3">820002-0000</span></td>
                <td><span class="tag tag-success" id="getProductName3">Site Office Rent/Warehouse</span></td>
                <td><span class="tag tag-success" id="getQty33">2</span></td>
                <td><span class="tag tag-success" id="getPrice3">@currency(10000000)</span></td>
                <td><span class="tag tag-success" id="totalArf3">@currency(60000000)</span></td>
                <td><span class="tag tag-success" id="getCurrency3">IDR</span></td>
                <!-- <td><span class="tag tag-success" id="getStatus3">{{$status3}}</span></td> -->
            </tr>
            <tr>
                @php
                    $totalApplied4 = round((((8-4) * 2000000) / 16000000 * 100),2);
                    $status4 = "";
                @endphp
                <td>
                @if($totalApplied4 == 100)
                    <a class="btn btn-outline-primary btn-sm float-right klikBudgetArf4" title="Submit" style="border-radius: 100px;color:blue;" disabled>
                        <i class="fas fa-file" aria-hidden="true"></i>
                    </a>
                    @else
                    <a class="btn btn-outline-success btn-sm float-right klikBudgetArf4" title="Submit" style="border-radius: 100px;color:green;">
                        <i class="fas fa-plus" aria-hidden="true"></i>
                    </a>
                    @endif
                </td>
                <td>
                    <div class="progress progress-xs" style="height: 14px;border-radius:8px;">
                        @if($totalApplied4 >= 0 && $totalApplied4 <= 40)
                        <div class="progress-bar bg-green" style="width:{{$totalApplied4}}%;"></div>
                        @elseif($totalApplied4 >= 41 && $totalApplied4 <= 89)
                        <div class="progress-bar bg-yellow" style="width:{{$totalApplied4}}%;"></div>
                        @elseif($totalApplied4 >= 90 && $totalApplied4 <= 100)
                        <div class="progress-bar bg-red" style="width:{{$totalApplied4}}%;"></div>
                        @endif
                    </div>
                    <small>
                        <center>{{$totalApplied4}} %</center>
                    </small>
                </td>
                <td><span class="tag tag-success" id="getWorkId4">6004</span></td>
                <td><span class="tag tag-success" id="getWorkName4">Project Overhead Actual</span></td>
                <td><span class="tag tag-success" id="getProductId4">820009-0000</span></td>
                <td><span class="tag tag-success" id="getProductName4">Entertainment</span></td>
                <td><span class="tag tag-success" id="getQty44">4</span></td>
                <td><span class="tag tag-success" id="getPrice4">@currency(2000000)</span></td>
                <td><span class="tag tag-success" id="totalArf4">@currency(16000000)</span></td>
                <td><span class="tag tag-success" id="getCurrency4">IDR</span></td>
                <!-- <td><span class="tag tag-success" id="getStatus4">{{$status4}}</span></td> -->
            </tr>
        </tbody>
    </table>
</div>