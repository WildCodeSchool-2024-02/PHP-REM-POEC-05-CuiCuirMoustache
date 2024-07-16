<?php

use App\Helper\Currency;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/config.php';

class CurrencyTest extends TestCase
{
    public function testConvert(): void
    {
        $value = 3.8;
        $expectedResult = '3,80 €';

        $currency = new Currency();
        $convert = $currency->intToCurrency($value);


        $this->assertSame($expectedResult, $convert,
            "La méthode intToCurrency ne fonctionne plus"
        );
    }
}