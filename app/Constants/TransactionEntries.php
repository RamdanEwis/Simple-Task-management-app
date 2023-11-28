<?php

namespace App\Constants;

final class TransactionEntries{
    const CREDIT = 1;
    const DEBIT = 2;

    public static function getTransactionEntries()
    {
        return [
        TransactionEntries::CREDIT => 'Credit',
        TransactionEntries::DEBIT => 'Debit',
        ];
    }

    public static function getTransactionEntry($key = '')
    {
        return !array_key_exists($key, self::getTransactionEntries()) ?
        " " : self::getTransactionEntries()[$key];
    }
}
