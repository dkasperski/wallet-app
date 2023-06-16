<?php

declare(strict_types=1);

namespace App\Wallet\Application\Service;

use App\Shared\CurrencyExchangeRates\CurrencyExchangeRatesInterface;
use App\Wallet\Domain\Entity\Operation;
use Money\Money;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WithdrawRuleForPrivate implements CalculateFeeInterface
{
    private const COMMISSION_VALUE = 0.003;

    private const FREE_OF_CHARGE_LIMIT_IN_EURO_CENT = 100000;

    private const NUMBER_OF_FREE_OF_CHARGE_WITHDRAWS_PER_WEEK = 3;

    private SessionInterface $session;

    private CurrencyExchangeRatesInterface $currencyExchangeRates;

    public function __construct(SessionInterface $session, CurrencyExchangeRatesInterface $currencyExchangeRates)
    {
        $this->session = $session;
        $this->currencyExchangeRates = $currencyExchangeRates;
    }

    // TODO would refactor this if have more time :)
    public function calculate(Operation $operation): Money
    {
        $previousUserOperationsInRequestedWeek = $this->getPreviousUserOperationsInRequestedWeek($operation);
        $operationAmountToCharge = $operation->getMoney();
        $operationCurrencyCode = $operationAmountToCharge->getCurrency()->getCode();
        $calculatedCommission = Money::$operationCurrencyCode(0);
        $rates = $this->currencyExchangeRates->fetchCurrencyExchangeRates()['rates'];

        if (count($previousUserOperationsInRequestedWeek) >= self::NUMBER_OF_FREE_OF_CHARGE_WITHDRAWS_PER_WEEK) {
            $calculatedCommission = $operationAmountToCharge->multiply(self::COMMISSION_VALUE);
        } elseif (!empty($previousUserOperationsInRequestedWeek)) {
            $operationAmountSum = 0;

            foreach ($previousUserOperationsInRequestedWeek as $operationData) {
                $operationCurrency = $operationData['operation_currency'];
                $operationAmount = $operationData['operation_amount'];
                if ($operationCurrency !== 'EUR') {
                    $rate = $rates[$operationCurrency];
                    $operationAmount /= $rate;
                }
                $operationAmountSum += $operationAmount;
            }

            if ($operationAmountSum * 100 >= self::FREE_OF_CHARGE_LIMIT_IN_EURO_CENT) {
                $calculatedCommission = $operationAmountToCharge->multiply(self::COMMISSION_VALUE);
            } else {
                $operationAmountToCharge = $operationAmountToCharge->add(Money::EUR((string) round($operationAmountSum * 100)));
                $operationAmountToCharge = $operationAmountToCharge->subtract(Money::EUR(self::FREE_OF_CHARGE_LIMIT_IN_EURO_CENT));
                $calculatedCommission = $operationAmountToCharge->multiply(self::COMMISSION_VALUE);
            }

        } elseif ((int) $operationAmountToCharge->getAmount() > self::FREE_OF_CHARGE_LIMIT_IN_EURO_CENT * $rates[$operationCurrencyCode]) {
            $operationAmountToCharge = $operationAmountToCharge->subtract(Money::$operationCurrencyCode(round(self::FREE_OF_CHARGE_LIMIT_IN_EURO_CENT * $rates[$operationCurrencyCode])));
            $calculatedCommission = $operationAmountToCharge->multiply(self::COMMISSION_VALUE);
        }

        $this->saveOperation($operation, $previousUserOperationsInRequestedWeek);
        return $calculatedCommission;
    }

    private function getPreviousUserOperationsInRequestedWeek(Operation $operation): array
    {
        $date = strtotime($operation->getOperationDate()->getValue());

        $previousOperationsInRequestedWeek = $this->session->get(
            sprintf('%s_%s.', $operation->getUserId()->getValue(), date("W_o", $date))
        );

        return $previousOperationsInRequestedWeek ?? [];
    }

    private function saveOperation(Operation $operation, array $previousUserOperationsInRequestedWeek): void
    {
        $date = strtotime($operation->getOperationDate()->getValue());
        $previousUserOperationsInRequestedWeek[] = $operation->toArray();

        $this->session->set(
            sprintf('%s_%s.', $operation->getUserId()->getValue(), date("W_o", $date)),
            $previousUserOperationsInRequestedWeek
        );
    }
}
