<?php

/*
+----------------------------------------------------------------------------------------------------------------------------------+
| ▪ Category   : API Engine Controller                                                                                             |
| ▪ Name Space : \App\Http\Controllers\Application\BackEnd\System\Transaction\Engines\update\budgeting                             |
|                \setBudgetExpenseLineCeiling\v1                                                                                   |
|                                                                                                                                  |
| ▪ Copyleft 🄯 2021 Zheta (teguhpjs@gmail.com)                                                                                     |
+----------------------------------------------------------------------------------------------------------------------------------+
*/
namespace App\Http\Controllers\Application\BackEnd\System\Transaction\Engines\update\budgeting\setBudgetExpenseLineCeiling\v1
    {
    /*
    +------------------------------------------------------------------------------------------------------------------------------+
    | ▪ Class Name  : setBudgetExpenseLineCeiling                                                                                      |
    | ▪ Description : Menangani API transaction.update.budgeting.setBudgetExpenseLineCeiling Version 1                                 |
    +------------------------------------------------------------------------------------------------------------------------------+
    */
    class setBudgetExpenseLineCeiling extends \App\Http\Controllers\Controller
        {
        /*
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Method Name     : __construct                                                                                          |
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Version         : 1.0000.0000000                                                                                       |
        | ▪ Last Update     : 2021-06-18                                                                                           |
        | ▪ Description     : System's Default Constructor                                                                         |
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Input Variable  :                                                                                                      |
        |      ▪ (void)                                                                                                            |
        | ▪ Output Variable :                                                                                                      |
        |      ▪ (void)                                                                                                            |
        +--------------------------------------------------------------------------------------------------------------------------+
        */
        function __construct()
            {
            }


        /*
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Method Name     : main                                                                                                 |
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Version         : 1.0000.0000000                                                                                       |
        | ▪ Last Update     : 2021-06-18                                                                                           |
        | ▪ Description     : Fungsi Utama Engine                                                                                  |
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Input Variable  :                                                                                                      |
        |      ▪ (mixed)  varUserSession ► User Session                                                                            |
        |      ▪ (array)  varData ► Data                                                                                           |
        | ▪ Output Variable :                                                                                                      |
        |      ▪ (string) varReturn                                                                                                |
        +--------------------------------------------------------------------------------------------------------------------------+
        */
        function main($varUserSession, $varData)
            {
            $varReturn = \App\Helpers\ZhtHelper\Logger\Helper_SystemLog::setLogOutputMethodHeader($varUserSession, null, __CLASS__, __FUNCTION__);
            try {
                $varSysDataProcess = \App\Helpers\ZhtHelper\Logger\Helper_SystemLog::setLogOutputMethodProcessHeader($varUserSession, __CLASS__, __FUNCTION__, 'Update Budget Expense Line Ceiling Data (version 1)');
                try {
                    //-----[ MAIN CODE ]----------------------------------------------------------------------------( START POINT )-----
                    try{
                        if(!($varDataSend = \App\Helpers\ZhtHelper\System\BackEnd\Helper_API::getEngineDataSend_DataUpdate($varUserSession, (new \App\Models\Database\SchData_OLTP_Budgeting\TblBudgetExpenseLineCeiling)->setDataUpdate(
                            $varUserSession,
                            $varData['recordID'],
                            null,
                            null,
                            (\App\Helpers\ZhtHelper\System\BackEnd\Helper_API::getUserLoginSessionEntityByAPIWebToken($varUserSession))['branchID'],
                            $varData['entities']['budgetExpenseLine_RefID'],
                            $varData['entities']['validStartDateTimeTZ'],
                            $varData['entities']['validFinishDateTimeTZ'],
                            $varData['entities']['currency_RefID'],
                            $varData['entities']['currencyExchangeRate'],
                            $varData['entities']['currencyValue']
                            ))))
                            {
                            throw new \Exception();
                            }
                        $varReturn = \App\Helpers\ZhtHelper\System\BackEnd\Helper_API::setEngineResponseDataReturn_Success($varUserSession, $varDataSend);
                        } 
                    catch (\Exception $ex) {
                        $varReturn = \App\Helpers\ZhtHelper\System\BackEnd\Helper_API::getEngineDataSend_DataUpdateException($varUserSession, $ex);
                        }
                    //-----[ MAIN CODE ]------------------------------------------------------------------------------( END POINT )-----
                    \App\Helpers\ZhtHelper\Logger\Helper_SystemLog::setLogOutputMethodProcessStatus($varUserSession, $varSysDataProcess, 'Success');
                    } 
                catch (\Exception $ex) {
                    $varReturn = \App\Helpers\ZhtHelper\System\BackEnd\Helper_API::setEngineResponseDataReturn_Fail($varUserSession, 401, $ex->getMessage());
                    \App\Helpers\ZhtHelper\Logger\Helper_SystemLog::setLogOutputMethodProcessStatus($varUserSession, $varSysDataProcess, 'Failed, '. $ex->getMessage());
                    }
                \App\Helpers\ZhtHelper\Logger\Helper_SystemLog::setLogOutputMethodProcessFooter($varUserSession, $varSysDataProcess);
                } 
            catch (\Exception $ex) {
                }
            return \App\Helpers\ZhtHelper\Logger\Helper_SystemLog::setLogOutputMethodFooter($varUserSession, $varReturn, __CLASS__, __FUNCTION__);
            }
        }
    }