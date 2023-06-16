<?php
declare(strict_types=1);

namespace App\Shared\CurrencyExchangeRates;

interface CurrencyExchangeRatesInterface
{
    public function fetchCurrencyExchangeRates(): array;
}