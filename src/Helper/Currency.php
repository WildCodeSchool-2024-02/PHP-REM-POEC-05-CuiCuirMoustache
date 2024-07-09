<?php

namespace App\Helper;

class Currency
{
    public static function intToCurrency(int|float $number): string
    {
        //1 ; 1.1 ; 1.11
        $strNumber = (string) $number;

        $parts = explode('.', $strNumber); // ['1'] ; ['1','1'] ; ['1','11']

        if (count($parts) == 1) {
            return $parts[0] . ",00 €";
        }

        //['1','1'] ; ['1','11']
        if (strlen($parts[1]) == 1) {//['1','1']
            return $parts[0] . "," . $parts[1] . "0 €";
        }

        return $parts[0] . "," . $parts[1] . " €";
    }
}