<?php

/*
+----------------------------------------------------------------------------------------------------------------------------------+
| ▪ Category    : Example - API Call Controller                                                                                    |
| ▪ Name Space  : \App\Http\Controllers\Application\FrontEnd\SandBox\Examples_APICall\transaction\read\dataList\humanResource      |
|                 \getWorkerCareerInternal\v1                                                                                      |
| ▪ API Key     : transaction.read.dataList.humanResource.getWorkerCareerInternal                                                  |
| ▪ API Version : 1                                                                                                                |
|                                                                                                                                  |
| ▪ Copyleft 🄯 2022 Zheta (teguhpjs@gmail.com)                                                                                     |
+----------------------------------------------------------------------------------------------------------------------------------+
*/
namespace App\Http\Controllers\Application\FrontEnd\SandBox\Examples_APICall\transaction\read\dataList\humanResource\getWorkerCareerInternal\v1
    {
    class example extends \App\Http\Controllers\Controller
        {
        /*
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Call URL        : http(s)://<HOST>/                                                                                    |
        |                     transaction.read.dataList.humanResource.getWorkerCareerInternal.v1_throughAPIGateway                 |
        |                     ► http://172.28.0.4/                                                                                 |
        |                       transaction.read.dataList.humanResource.getWorkerCareerInternal.v1_throughAPIGateway               |
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Version         : 1.0000.0000000                                                                                       |
        | ▪ Last Update     : 2022-09-13                                                                                           |
        | ▪ Creation Date   : 2022-09-13                                                                                           |
        +--------------------------------------------------------------------------------------------------------------------------+
        */
        public function throughAPIGateway($varAPIWebToken)
            {
            //---Parameter Set---
            if (!$varAPIWebToken) {
                $varAPIWebToken = \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System();
                }
            //---Core---
            $varData = \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGateway(
                \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
                $varAPIWebToken, 
                'transaction.read.dataList.humanResource.getWorkerCareerInternal', 
                'latest', 
                [
                'parameter' => [
                    'worker_RefID' => 32000000000012,
                    'dateTimeTZ' => null
                    ],
                'SQLStatement' => [
                    'pick' => null,
                    'sort' => null,
                    'filter' => null,
                    'paging' => null
                    ]
                ]
                );
            var_dump($varData);
            }


        /*
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Call URL        : http(s)://<HOST>/                                                                                    |
        |                     transaction.read.dataList.humanResource.getWorkerCareerInternal.v1_throughAPIGatewayJQuery           |
        |                     ► http://172.28.0.4/                                                                                 |
        |                       transaction.read.dataList.humanResource.getWorkerCareerInternal.v1_throughAPIGatewayJQuery         |
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Version         : 1.0000.0000000                                                                                       |
        | ▪ Last Update     : 2022-09-13                                                                                           |
        | ▪ Creation Date   : 2022-09-13                                                                                           |
        +--------------------------------------------------------------------------------------------------------------------------+
        */
        public function throughAPIGatewayJQuery($varAPIWebToken)
            {
            //---Parameter Set---
            if (!$varAPIWebToken) {
                $varAPIWebToken = \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System();
                }
            //---Core---
            echo \App\Helpers\ZhtHelper\General\Helper_JavaScript::setLibrary(\App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System());
            echo '<table border="1" style="border-collapse: collapse;">';
            echo '<tr><td colspan="2" bgcolor="#6666cc" align="middle"><p style="color:#ffffff">Parameter</p></td></tr>';
            echo '<tr><td>Person&nbsp;Work&nbsp;Time&nbsp;Shee&nbsp;RefID</td><td><input type="text" id="dataInput_PersonWorkTimeSheet_RefID" value=""></td></tr>';
            echo '<tr><td>DateTimeTZ</td><td><input type="text" id="dataInput_DateTimeTZ" value=""></td></tr>';
            echo '<tr><td colspan="2" bgcolor="#6666cc" align="middle"><p style="color:#ffffff">SQL Statement</p></td></tr>';
            echo '<tr><td>Pick</td><td><input type="text" id="dataInput_SQLStatement_pick" value=""></td></tr>';
            echo '<tr><td>Sort</td><td><input type="text" id="dataInput_SQLStatement_sort" value=""></td></tr>';
            echo '<tr><td>Filter</td><td><input type="text" id="dataInput_SQLStatement_filter" value=""></td></tr>';
            echo '<tr><td>Paging</td><td> <input type="text" id="dataInput_SQLStatement_paging" value=""></td></tr>';
            echo '</table>';
            $varJQueryFunction = \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGatewayJQuery(
                \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(), 
                $varAPIWebToken, 
                'transaction.read.dataList.humanResource.getWorkerCareerInternal', 
                'latest', 
                '{'.
                    '"parameter" : {'.
                        '"worker_RefID" : parseInt(document.getElementById("dataInput_Worker_RefID").value), '.
                        '"dateTimeTZ" : document.getElementById("dataInput_DateTimeTZ").value'.
                        '}, '.
                    '"SQLStatement" : {'.
                        '"pick" : document.getElementById("dataInput_SQLStatement_pick").value, '.
                        '"sort" : document.getElementById("dataInput_SQLStatement_sort").value, '.
                        '"filter" : document.getElementById("dataInput_SQLStatement_filter").value, '.
                        '"paging" : document.getElementById("dataInput_SQLStatement_paging").value'.
                        '}'.
                '}'
                ); 
            echo "<button type='button' onclick='javascript:var varData = ".$varJQueryFunction."; $(\"body\").append(JSON.stringify(varData));'>Submit Data</button>";
            dd($varJQueryFunction);
            }
        }
    }