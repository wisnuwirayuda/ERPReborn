<?php

/*
+----------------------------------------------------------------------------------------------------------------------------------+
| ▪ Category   : Laravel Models                                                                                                    |
| ▪ Name Space : \App\Models\Database\SchData_OLTP_Finance                                                                         |
|                                                                                                                                  |
| ▪ Copyleft 🄯 2022 - 2025 Zheta (teguhpjs@gmail.com)                                                                              |
+----------------------------------------------------------------------------------------------------------------------------------+
*/
namespace App\Models\Database\SchData_OLTP_Finance
    {
    /*
    +------------------------------------------------------------------------------------------------------------------------------+
    | ▪ Class Name  : TblBankAccountMutationDetail                                                                                 |
    | ▪ Description : Menangani Models Database ► SchData-OLTP-Finance ► TblBankAccountMutationDetail                              |
    +------------------------------------------------------------------------------------------------------------------------------+
    */
    class TblBankAccountMutationDetail extends \App\Models\Database\DefaultClassPrototype
        {
        /*
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Method Name     : __construct                                                                                          |
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Version         : 1.0000.0000000                                                                                       |
        | ▪ Last Update     : 2022-10-04                                                                                           |
        | ▪ Creation Date   : 2022-10-04                                                                                           |
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
            parent::__construct(__CLASS__);
            }


        /*
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Method Name     : setDataInsert                                                                                        |
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Version         : 1.0001.0000000                                                                                       |
        | ▪ Last Update     : 2025-02-03                                                                                           |
        | ▪ Creation Date   : 2022-10-04                                                                                           |
        | ▪ Description     : Data Insert                                                                                          |
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Input Variable  :                                                                                                      |
        |      ▪ (mixed)  varUserSession ► User Session                                                                            |
        |      ▪ (string) varSysDataAnnotation ► System Data Annotation                                                            |
        |      ▪ (string) varSysDataValidityStartDateTimeTZ ► System Data Validity Start DateTimeTZ                                |
        |      ▪ (string) varSysDataValidityFinishDateTimeTZ ► System Validity Finish DateTimeTZ                                   |
        |      ▪ (string) varSysPartitionRemovableRecordKeyRefType ► System Partition Removable Record Key Reference Type          |
        |      ▪ (int)    varSysBranch_RefID ► System Branch Reference ID                                                          |
        |      ▪ (int)    varSysBaseCurrency_RefID ► System Base Currency Reference ID                                             |
        |        ----------------------------------------                                                                          |
        |      ▪ (int)    varBankAccountMutation_RefID ► Bank Account Mutation Reference ID                                        |
        |      ▪ (string) varMutationDateTimeTZ ► Mutation DateTimeTZ                                                              |
        |      ▪ (int)    varAccountingEntryRecordType_RefID ► Accounting Entry Record Type Reference ID                           |
        |      ▪ (int)    varAmountCurrency_RefID ► Amount Currency Reference ID                                                   |
        |      ▪ (float)  varAmountCurrencyValue ► Amount Currency Value                                                           |
        |      ▪ (float)  varAmountCurrencyExchangeRate ► Amount Currency Exchange Rate                                            |
        |      ▪ (string) varDescription ► Description                                                                             |
        | ▪ Output Variable :                                                                                                      |
        |      ▪ (array)  varReturn                                                                                                | 
        +--------------------------------------------------------------------------------------------------------------------------+
        */
        public function setDataInsert(
            $varUserSession,
            string $varSysDataAnnotation = null, string $varSysDataValidityStartDateTimeTZ = null, string $varSysDataValidityFinishDateTimeTZ = null, int $varSysPartitionRemovableRecordKeyRefType = null, int $varSysBranch_RefID = null, $varSysBaseCurrency_RefID = null,
            int $varBankAccountMutation_RefID = null, string $varMutationDateTimeTZ = null, int $varAccountingEntryRecordType_RefID = null, int $varAmountCurrency_RefID = null, float $varAmountCurrencyValue = null, float $varAmountCurrencyExchangeRate = null, string $varDescription = null
            )
            {
            $varReturn =
                \App\Helpers\ZhtHelper\Database\Helper_PostgreSQL::getQueryExecution(
                    $varUserSession, 
                    \App\Helpers\ZhtHelper\Database\Helper_PostgreSQL::getBuildStringLiteral_StoredProcedure(
                        $varUserSession,
                        parent::getSchemaName($varUserSession).'.Func_'.parent::getTableName($varUserSession).'_SET',
                        [
                            [$varUserSession, 'bigint'],
                            [null, 'bigint'],

                            [$varSysDataAnnotation, 'varchar'],
                            [$varSysDataValidityStartDateTimeTZ, 'timestamptz'],
                            [$varSysDataValidityFinishDateTimeTZ, 'timestamptz'],
                            [$varSysPartitionRemovableRecordKeyRefType, 'varchar'],
                            [$varSysBranch_RefID, 'bigint'],
                            [$varSysBaseCurrency_RefID, 'bigint'],

                            [$varBankAccountMutation_RefID, 'bigint'],
                            [$varMutationDateTimeTZ, 'timestamptz'],
                            [$varAccountingEntryRecordType_RefID, 'bigint'],
                            [$varAmountCurrency_RefID, 'bigint'],
                            [$varAmountCurrencyValue, 'numeric'],
                            [$varAmountCurrencyExchangeRate, 'numeric'],
                            [$varDescription, 'varchar']
                        ]
                        )
                    );

            return
                $varReturn;
            }


        /*
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Method Name     : setDataUpdate                                                                                        |
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Version         : 1.0001.0000000                                                                                       |
        | ▪ Last Update     : 2025-02-03                                                                                           |
        | ▪ Creation Date   : 2022-10-04                                                                                           |
        | ▪ Description     : Data Update                                                                                          |
        +--------------------------------------------------------------------------------------------------------------------------+
        | ▪ Input Variable  :                                                                                                      |
        |      ▪ (mixed)  varUserSession ► User Session                                                                            |
        |      ▪ (int)    varSysID ► System Record ID                                                                              |
        |      ▪ (string) varSysDataAnnotation ► System Data Annotation                                                            |
        |      ▪ (string) varSysDataValidityStartDateTimeTZ ► System Data Validity Start DateTimeTZ                                |
        |      ▪ (string) varSysDataValidityFinishDateTimeTZ ► System Validity Finish DateTimeTZ                                   |
        |      ▪ (string) varSysPartitionRemovableRecordKeyRefType ► System Partition Removable Record Key Reference Type          |
        |      ▪ (int)    varSysBranch_RefID ► System Branch Reference ID                                                          |
        |      ▪ (int)    varSysBaseCurrency_RefID ► System Base Currency Reference ID                                             |
        |        ----------------------------------------                                                                          |
        |      ▪ (int)    varBankAccountMutation_RefID ► Bank Account Mutation Reference ID                                        |
        |      ▪ (string) varMutationDateTimeTZ ► Mutation DateTimeTZ                                                              |
        |      ▪ (int)    varAccountingEntryRecordType_RefID ► Accounting Entry Record Type Reference ID                           |
        |      ▪ (int)    varAmountCurrency_RefID ► Amount Currency Reference ID                                                   |
        |      ▪ (float)  varAmountCurrencyValue ► Amount Currency Value                                                           |
        |      ▪ (float)  varAmountCurrencyExchangeRate ► Amount Currency Exchange Rate                                            |
        |      ▪ (string) varDescription ► Description                                                                             |
        | ▪ Output Variable :                                                                                                      |
        |      ▪ (array)  varReturn                                                                                                | 
        +--------------------------------------------------------------------------------------------------------------------------+
        */
        public function setDataUpdate(
            $varUserSession,
            int $varSysID,
            string $varSysDataAnnotation = null, string $varSysDataValidityStartDateTimeTZ = null, string $varSysDataValidityFinishDateTimeTZ = null, int $varSysPartitionRemovableRecordKeyRefType = null, int $varSysBranch_RefID = null, $varSysBaseCurrency_RefID = null,
            int $varBankAccountMutation_RefID = null, string $varMutationDateTimeTZ = null, int $varAccountingEntryRecordType_RefID = null, int $varAmountCurrency_RefID = null, float $varAmountCurrencyValue = null, float $varAmountCurrencyExchangeRate = null, string $varDescription = null
            )
            {
            $varReturn =
                \App\Helpers\ZhtHelper\Database\Helper_PostgreSQL::getQueryExecution(
                    $varUserSession, 
                    \App\Helpers\ZhtHelper\Database\Helper_PostgreSQL::getBuildStringLiteral_StoredProcedure(
                        $varUserSession,
                        parent::getSchemaName($varUserSession).'.Func_'.parent::getTableName($varUserSession).'_SET',
                        [
                            [$varUserSession, 'bigint'],
                            [$varSysID, 'bigint'],

                            [$varSysDataAnnotation, 'varchar'],
                            [$varSysDataValidityStartDateTimeTZ, 'timestamptz'],
                            [$varSysDataValidityFinishDateTimeTZ, 'timestamptz'],
                            [$varSysPartitionRemovableRecordKeyRefType, 'varchar'],
                            [$varSysBranch_RefID, 'bigint'],
                            [$varSysBaseCurrency_RefID, 'bigint'],

                            [$varBankAccountMutation_RefID, 'bigint'],
                            [$varMutationDateTimeTZ, 'timestamptz'],
                            [$varAccountingEntryRecordType_RefID, 'bigint'],
                            [$varAmountCurrency_RefID, 'bigint'],
                            [$varAmountCurrencyValue, 'numeric'],
                            [$varAmountCurrencyExchangeRate, 'numeric'],
                            [$varDescription, 'varchar']
                        ]
                        )
                    );

            return
                $varReturn;
            }
        }
    }