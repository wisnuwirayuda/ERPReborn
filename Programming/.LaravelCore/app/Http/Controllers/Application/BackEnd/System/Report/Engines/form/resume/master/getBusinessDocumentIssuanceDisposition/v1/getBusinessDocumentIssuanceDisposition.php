<?php

/*
+----------------------------------------------------------------------------------------------------------------------------------+
| ▪ Category   : API Engine Controller                                                                                             |
| ▪ Name Space : \App\Http\Controllers\Application\BackEnd\System\Report\Engines\form\resume\master                                |
|                \getBusinessDocumentIssuanceDisposition\v1                                                                        |
|                                                                                                                                  |
| ▪ Copyleft 🄯 2023 Zheta (teguhpjs@gmail.com)                                                                                     |
+----------------------------------------------------------------------------------------------------------------------------------+
*/

namespace App\Http\Controllers\Application\BackEnd\System\Report\Engines\form\resume\master\getBusinessDocumentIssuanceDisposition\v1
    {
    /*
    +------------------------------------------------------------------------------------------------------------------------------+
    | ▪ Class Name  : getBusinessDocumentIssuanceDisposition                                                                       |
    | ▪ Description : Menangani API report.form.resume.master.getBusinessDocumentIssuanceDisposition Version 1                     |
    +------------------------------------------------------------------------------------------------------------------------------+
    */
    class getBusinessDocumentIssuanceDisposition extends \App\Http\Controllers\Controller
        {
        /*
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Method Name     : __construct                                                                                          |
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Version         : 1.0000.0000000                                                                                       |
        | ▪ Last Update     : 2023-06-20                                                                                           |
        | ▪ Create date     : 2023-06-20                                                                                           |
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
        | ▪ Last Update     : 2023-06-20                                                                                           |
        | ▪ Create date     : 2023-06-20                                                                                           |
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
            /*
            $userSessionID = 
                App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System();

            $branchID = 
                \App\Helpers\ZhtHelper\System\BackEnd\Helper_API::getUserLoginSessionEntityByAPIWebToken(
                    $userSessionID
                    )['branchID'];

            $workerCareerInternal_RefID =
                \App\Helpers\ZhtHelper\System\BackEnd\Helper_API::getUserLoginSessionEntityByAPIWebToken(
                    $userSessionID
                    )['userIdentity']['workerCareerInternal_RefID'];

            $varTTL = 86400; // 24 Jam
            // GET DATA MASTER ShowMyDocumentListData 
            $varShowMyDocumentListData =
                (new \App\Models\Database\SchData_OLTP_Master\General())->getReport_Form_Resume_BusinessDocumentIssuanceDisposition(
                    $userSessionID,
                    $branchID,
                    $workerCareerInternal_RefID
                );

            //SET REDIS ShowMyDocumentListData

            \App\Helpers\ZhtHelper\Cache\Helper_Redis::setValue(
                $userSessionID,
                "ShowMyDocumentListData" . $workerCareerInternal_RefID,
                json_encode($varShowMyDocumentListData[0]['document']['content']['itemList']['ungrouped']),
                // $varTTL
            );

            return [];
            
            */

            $varReturn = \App\Helpers\ZhtHelper\Logger\Helper_SystemLog::setLogOutputMethodHeader($varUserSession, null, __CLASS__, __FUNCTION__);
                try {
                    $varSysDataProcess = \App\Helpers\ZhtHelper\Logger\Helper_SystemLog::setLogOutputMethodProcessHeader($varUserSession, __CLASS__, __FUNCTION__, 'Get Report Form - Resume - Business Document Issuance Disposition Form (version 1)');
                    try {
                    //-----[ MAIN CODE ]----------------------------------------------------------------------------( START POINT )-----
                    try {
                        if (!($varDataSend = 
                            \App\Helpers\ZhtHelper\System\BackEnd\Helper_API::getEngineDataSend_DataRead(
                                $varUserSession,
                                (new \App\Models\Database\SchData_OLTP_Master\General())->getReport_Form_Resume_BusinessDocumentIssuanceDisposition(
                                    $varUserSession,
                                    (\App\Helpers\ZhtHelper\System\BackEnd\Helper_API::getUserLoginSessionEntityByAPIWebToken($varUserSession))['branchID'],

                                    $varData['parameter']['recordID'],

                                    (\App\Helpers\ZhtHelper\General\Helper_Array::isKeyExist(
                                        $varUserSession,
                                        'businessDocumentNumber',
                                        $varData['parameter']['dataFilter']
                                        ) ? (
                                            (!is_null($varData['parameter']['dataFilter']['businessDocumentNumber'])) 
                                                ? $varData['parameter']['dataFilter']['businessDocumentNumber'] 
                                                : null
                                                ) 
                                            : null
                                    ),
                                    (\App\Helpers\ZhtHelper\General\Helper_Array::isKeyExist(
                                        $varUserSession,
                                        'businessDocumentType_RefID',
                                        $varData['parameter']['dataFilter']
                                        ) ? (
                                            (!is_null($varData['parameter']['dataFilter']['businessDocumentType_RefID'])) 
                                                ? $varData['parameter']['dataFilter']['businessDocumentType_RefID']
                                                : null
                                                )
                                            : null
                                    ),
                                    (
                                    \App\Helpers\ZhtHelper\General\Helper_Array::isKeyExist(
                                        $varUserSession,
                                        'combinedBudget_RefID',
                                        $varData['parameter']['dataFilter']
                                        ) ? (
                                            (!is_null($varData['parameter']['dataFilter']['combinedBudget_RefID']))
                                                ? $varData['parameter']['dataFilter']['combinedBudget_RefID']
                                                : null
                                                )
                                            : null
                                    )

                                    )
                                )
                            ))
                            {
                            throw new \Exception();
                            }

                        $varReturn =
                            \App\Helpers\ZhtHelper\System\BackEnd\Helper_API::setEngineResponseDataReturn_Success(
                                $varUserSession,
                                $varDataSend
                                );
                        }

                    catch (\Exception $ex) {
                        $varErrorMessage = $ex->getMessage();
                        $varReturn =
                            \App\Helpers\ZhtHelper\System\BackEnd\Helper_API::setEngineResponseDataReturn_Fail(
                                $varUserSession,
                                500,
                                'Invalid SQL Syntax'.($varErrorMessage ? ' ('.$varErrorMessage.')' : '')
                            );
                        }
                     //-----[ MAIN CODE ]------------------------------------------------------------------------------( END POINT )-----
                    \App\Helpers\ZhtHelper\Logger\Helper_SystemLog::setLogOutputMethodProcessStatus($varUserSession, $varSysDataProcess, 'Success');
                    }

                catch (\Exception $ex) {
                    $varReturn =
                        \App\Helpers\ZhtHelper\System\BackEnd\Helper_API::setEngineResponseDataReturn_Fail(
                            $varUserSession,
                            401,
                            $ex->getMessage()
                            );

                    \App\Helpers\ZhtHelper\Logger\Helper_SystemLog::setLogOutputMethodProcessStatus($varUserSession, $varSysDataProcess, 'Failed, '. $ex->getMessage());
                    }
                \App\Helpers\ZhtHelper\Logger\Helper_SystemLog::setLogOutputMethodProcessFooter($varUserSession, $varSysDataProcess);
                }

            catch (\Exception $ex) {
                }
                
            return
                \App\Helpers\ZhtHelper\Logger\Helper_SystemLog::setLogOutputMethodFooter($varUserSession, $varReturn, __CLASS__, __FUNCTION__);
            }
        }
    }
