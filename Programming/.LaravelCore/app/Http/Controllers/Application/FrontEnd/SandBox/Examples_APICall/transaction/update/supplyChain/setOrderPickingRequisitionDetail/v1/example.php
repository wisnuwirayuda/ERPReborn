<?php

/*
+----------------------------------------------------------------------------------------------------------------------------------+
| ▪ Category    : Example - API Call Controller                                                                                    |
| ▪ Name Space  : \App\Http\Controllers\Application\FrontEnd\SandBox\Examples_APICall\transaction\update\supplyChain               |
|                 \setOrderPickingRequisitionDetail\v1                                                                             |
| ▪ API Key     : transaction.update.supplyChain.setOrderPickingRequisitionDetail                                                  |
| ▪ API Version : 1                                                                                                                |
|                                                                                                                                  |
| ▪ Copyleft 🄯 2024 - 2025 Zheta (teguhpjs@gmail.com)                                                                              |
+----------------------------------------------------------------------------------------------------------------------------------+
*/
namespace App\Http\Controllers\Application\FrontEnd\SandBox\Examples_APICall\transaction\update\supplyChain\setOrderPickingRequisitionDetail\v1
    {
    class example extends \App\Http\Controllers\Controller
        {
        /*
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Call URL        : http(s)://<HOST>/                                                                                    |
        |                     transaction.update.supplyChain.setOrderPickingRequisitionDetail.v1_throughAPIGateway                 |
        |                     ► http://172.28.0.4/                                                                                 |
        |                       transaction.update.supplyChain.setOrderPickingRequisitionDetail.v1_throughAPIGateway               |
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Version         : 1.0000.0000000                                                                                       |
        | ▪ Last Update     : 2024-01-09                                                                                           |
        | ▪ Creation Date   : 2024-01-09                                                                                           |
        +--------------------------------------------------------------------------------------------------------------------------+
        */
        public function throughAPIGateway($varAPIWebToken)
            {
            //---Parameter Set---
            if (!$varAPIWebToken) {
                $varAPIWebToken =
                    \App\Helpers\ZhtHelper\System\Helper_Environment::getAPIWebToken_System();
                }

            //---Core---
            $varData =
                \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGateway(
                    //-----[ METADATA ]-----( START )-----
                        \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
                        $varAPIWebToken, 
                        'transaction.update.supplyChain.setOrderPickingRequisitionDetail', 
                        'latest',
                    //-----[ METADATA ]-----(  END  )-----

                    //-----[ DATA ]-----( START )-----
                        [
                        'recordID' => 249000000000001,
                        'entities' => [
                            "orderPickingRequisition_RefID" => 248000000000001,
                            "purchaseRequisitionDetail_RefID" => 84000000000001,
                            "product_RefID" => 88000000000002,
                            "quantity" => 10,
                            "quantityUnit_RefID" => 73000000000001,
                            "productUnitPriceCurrency_RefID" => 62000000000001,
                            "productUnitPriceCurrencyValue" => 30000,
                            "productUnitPriceCurrencyExchangeRate" => 1,
                            "fulfillmentDeadlineDateTimeTZ" => '2023-03-01',
                            "remarks" => 'Catatan'
                            ]
                        ]
                    //-----[ DATA ]-----(  END  )-----
                    );

            var_dump($varData);
            }


        /*
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Call URL        : http(s)://<HOST>/                                                                                    |
        |                     transaction.update.supplyChain.setOrderPickingRequisitionDetail.v1_throughAPIGatewayJQuery           |
        |                     ► http://172.28.0.4/                                                                                 |
        |                       transaction.update.supplyChain.setOrderPickingRequisitionDetail.v1_throughAPIGatewayJQuery         |
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Version         : 1.0000.0000000                                                                                       |
        | ▪ Last Update     : 2024-01-09                                                                                           |
        | ▪ Creation Date   : 2024-01-09                                                                                           |
        +--------------------------------------------------------------------------------------------------------------------------+
        */
        public function throughAPIGatewayJQuery($varAPIWebToken)
            {
            //---Parameter Set---
            if (!$varAPIWebToken) {
                $varAPIWebToken =
                    \App\Helpers\ZhtHelper\System\Helper_Environment::getAPIWebToken_System();
                }

            //---Core---
            echo \App\Helpers\ZhtHelper\General\Helper_JavaScript::setLibrary(\App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System());

            echo '<table border="1" style="border-collapse: collapse;">';
            echo    '<tr><td colspan="2" bgcolor="#6666cc" align="middle"><p style="color:#ffffff">Order Picking Requisition Detail Main Data</p></td></tr>';
            echo       '<tr><td>RecordID</td><td><input type="text" id="dataInput_RecordID" value=249000000000001></td></tr>';
            echo       '<tr><td>OrderPickingRequisition_RefID</td><td><input type="text" id="dataInput_OrderPickingRequisition_RefID" value=248000000000001></td></tr>';
            echo       '<tr><td>PurchaseRequisitionDetail_RefID</td><td><input type="text" id="dataInput_PurchaseRequisitionDetail_RefID" value=84000000000001></td></tr>';
            echo       '<tr><td>Product_RefID</td><td><input type="text" id="dataInput_Product_RefID" value=88000000000002></td></tr>';
            echo       '<tr><td>Quantity</td><td><input type="text" id="dataInput_Quantity" value=10></td></tr>';
            echo       '<tr><td>QuantityUnit_RefID</td><td><input type="text" id="dataInput_QuantityUnit_RefID" value=73000000000001></td></tr>';
            echo       '<tr><td>ProductUnitPriceCurrency_RefID</td><td><input type="text" id="dataInput_ProductUnitPriceCurrency_RefID" value=62000000000001></td></tr>';
            echo       '<tr><td>ProductUnitPriceCurrencyValue</td><td><input type="text" id="dataInput_ProductUnitPriceCurrencyValue" value=30000></td></tr>';
            echo       '<tr><td>ProductUnitPriceCurrencyExchangeRate</td><td><input type="text" id="dataInput_ProductUnitPriceCurrencyExchangeRate" value=1></td></tr>';
            echo       '<tr><td>FulfillmentDeadlineDateTimeTZ</td><td><input type="text" id="dataInput_FulfillmentDeadlineDateTimeTZ" value="2023-03-01"></td></tr>';
            echo       '<tr><td>Remarks</td><td><input type="text" id="dataInput_Remarks" value="Catatan"></td></tr>';
            echo '</table><br>';

            $varJQueryFunction =
                \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGatewayJQuery(
                    //-----[ METADATA ]-----( START )-----
                        \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(), 
                        $varAPIWebToken, 
                        'transaction.update.supplyChain.setOrderPickingRequisitionDetail', 
                        'latest',
                    //-----[ METADATA ]-----(  END  )-----

                    //-----[ DATA ]-----( START )-----
                        '{'.
                            '"recordID" : parseInt(document.getElementById("dataInput_RecordID").value), '.
                            '"entities" : {'.
                                '"orderPickingRequisition_RefID" : parseInt(document.getElementById("dataInput_OrderPickingRequisition_RefID").value), '.
                                '"purchaseRequisitionDetail_RefID" : parseInt(document.getElementById("dataInput_PurchaseRequisitionDetail_RefID").value), '.
                                '"product_RefID" : parseInt(document.getElementById("dataInput_Product_RefID").value), '.
                                '"quantity" : parseFloat(document.getElementById("dataInput_Quantity").value), '.
                                '"quantityUnit_RefID" : parseInt(document.getElementById("dataInput_QuantityUnit_RefID").value), '.
                                '"productUnitPriceCurrency_RefID" : parseInt(document.getElementById("dataInput_ProductUnitPriceCurrency_RefID").value), '.
                                '"productUnitPriceCurrencyValue" : parseFloat(document.getElementById("dataInput_ProductUnitPriceCurrencyValue").value), '.
                                '"productUnitPriceCurrencyExchangeRate" : parseFloat(document.getElementById("dataInput_ProductUnitPriceCurrencyExchangeRate").value), '.
                                '"fulfillmentDeadlineDateTimeTZ" : document.getElementById("dataInput_FulfillmentDeadlineDateTimeTZ").value, '.
                                '"remarks" : document.getElementById("dataInput_Remarks").value'.
                                '}'.
                        '}'
                    //-----[ DATA ]-----(  END  )-----
                    ); 

            echo "<button type='button' onclick='javascript:var varData = ".$varJQueryFunction."; $(\"body\").append(JSON.stringify(varData));'>Submit Data</button>";

            dd($varJQueryFunction);
            }
        }
    }