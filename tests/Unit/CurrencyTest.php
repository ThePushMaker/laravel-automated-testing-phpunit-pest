<?php

namespace Tests\Unit;

use App\Services\CurrencyService;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_convert_usd_to_eur_successful(): void
    {
        $this->assertEquals(92,
            (new CurrencyService())->convert(100, 'usd', 'eur')
        );
    }
    
    public function test_convert_usd_to_gbp_returns_zero(): void
    {
        $this->assertEquals(0, 
            (new CurrencyService())->convert(100, 'usd', 'gbp')
        );
    }
}
