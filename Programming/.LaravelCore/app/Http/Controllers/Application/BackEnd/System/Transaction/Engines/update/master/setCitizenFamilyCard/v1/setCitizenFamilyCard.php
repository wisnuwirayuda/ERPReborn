<?php

/*
+----------------------------------------------------------------------------------------------------------------------------------+
| ▪ Category   : API Engine Controller                                                                                             |
| ▪ Name Space : \App\Http\Controllers\Application\BackEnd\System\Transaction\Engines\update\master\setCitizenFamilyCard\v1        |
|                                                                                                                                  |
| ▪ Copyleft 🄯 2021 Zheta (teguhpjs@gmail.com)                                                                                     |
+----------------------------------------------------------------------------------------------------------------------------------+
*/
namespace App\Http\Controllers\Application\BackEnd\System\Transaction\Engines\update\master\setCitizenFamilyCard\v1
    {
    /*
    +------------------------------------------------------------------------------------------------------------------------------+
    | ▪ Class Name  : setCitizenFamilyCard                                                                                         |
    | ▪ Description : Menangani API transaction.update.master.setCitizenFamilyCard Version 1                                       |
    +------------------------------------------------------------------------------------------------------------------------------+
    */
    class setCitizenFamilyCard extends \App\Http\Controllers\Controller
        {
        /*
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Method Name     : __construct                                                                                          |
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Version         : 1.0000.0000000                                                                                       |
        | ▪ Last Update     : 2021-07-29                                                                                           |
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
        | ▪ Last Update     : 2021-07-29                                                                                           |
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
                $varSysDataProcess = \App\Helpers\ZhtHelper\Logger\Helper_SystemLog::setLogOutputMethodProcessHeader($varUserSession, __CLASS__, __FUNCTION__, 'Update Citizen Family Card Data (version 1)');
                try {
                    //-----[ MAIN CODE ]----------------------------------------------------------------------------( START POINT )-----
                    try{
                        if(!($varDataSend = \App\Helpers\ZhtHelper\System\BackEnd\Helper_API::getEngineDataSend_DataUpdate($varUserSession, (new \App\Models\Database\SchData_OLTP_Master\TblCitizenFamilyCard())->setDataUpdate(
                            $varUserSession,
                            $varData['recordID'],
                            null,
                            null,
                            (\App\Helpers\ZhtHelper\System\BackEnd\Helper_API::getUserLoginSessionEntityByAPIWebToken($varUserSession))['branchID'],
                            \App\Helpers\ZhtHelper\System\BackEnd\Helper_FileUpload::setUploadPointer(
                                $varUserSession,
                                $varData['entities']['log_FileUpload_Pointer_RefID'],
                                (new \App\Models\Database\SchSysConfig\General())->getYearByDate($varUserSession, $varData['entities']['issuedDate'])
                                ),
                            $varData['entities']['cardNumber'],
                            $varData['entities']['issuedDate'],
                            $varData['entities']['addressCountryAdministrativeAreaLevel1_RefID'],
                            $varData['entities']['addressCountryAdministrativeAreaLevel2_RefID'],
                            $varData['entities']['addressCountryAdministrativeAreaLevel3_RefID'],
                            $varData['entities']['addressCountryAdministrativeAreaLevel4_RefID'],
                            $varData['entities']['address'],
                            $varData['entities']['addressNeighbourhoodNumber'],
                            $varData['entities']['addressHamletNumber'],
                            $varData['entities']['postalCode'],
                            $varData['entities']['cardSerialNumber']
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