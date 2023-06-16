<?php

declare(strict_types=1);

namespace App\Shared\CurrencyExchangeRates\Http;

use App\Shared\CurrencyExchangeRates\CurrencyExchangeRatesInterface;
use App\Shared\CurrencyExchangeRates\Http\Exception\CurrencyExchangeRatesException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyExchangeRates implements CurrencyExchangeRatesInterface
{
    private HttpClientInterface $client;

    private ParameterBagInterface $parameterBag;

    public function __construct(HttpClientInterface $client, ParameterBagInterface $parameterBag)
    {
        $this->client = $client;
        $this->parameterBag = $parameterBag;
    }

    public function fetchCurrencyExchangeRates(): array
    {
        $response = $this->client->request(
            'GET',
            $this->parameterBag->get('currency_exchange_rates.api')
        );

        if ($response->getStatusCode() !== 200) {
            throw new CurrencyExchangeRatesException();
        }

        $contentType = $response->getHeaders()['content-type'][0];
        if ($contentType !== 'application/json') {
            throw new CurrencyExchangeRatesException();
        }
        return $response->toArray();
    }
}
