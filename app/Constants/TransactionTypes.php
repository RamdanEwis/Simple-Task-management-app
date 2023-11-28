<?php

 namespace App\Constants;

 final class TransactionTypes{
     const SALES = 1;
     const REFUND = 2;
     const COMMISSION = 3;
     const REVERSION = 4;

     public static function getTransactionTypes()
     {
         return [
            TransactionTypes::SALES => 'Sales',
            TransactionTypes::REFUND => 'Refund',
            TransactionTypes::COMMISSION => 'Commission',
            TransactionTypes::REVERSION => 'Reversion',
         ];
     }

     public static function getTransactionType($key = '')
     {
         return !array_key_exists($key, self::getTransactionTypes()) ?
          " " : self::getTransactionTypes()[$key];
     }
 }
