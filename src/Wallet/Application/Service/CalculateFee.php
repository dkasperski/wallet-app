<?php

declare(strict_types=1);

namespace App\Wallet\Application\Service;

use App\Wallet\Domain\Entity\Operation;
use Money\Money;

class CalculateFee
{
    private CalculateFeeInterface $calculateFee;

    public function __construct(CalculateFeeInterface $calculateFee)
    {
        $this->calculateFee = $calculateFee;
    }

    public function setCalculateFeeRule(CalculateFeeInterface $calculateFee)
    {
        $this->calculateFee = $calculateFee;
    }

    public function calculate(Operation $operation): Money
    {
        return $this->calculateFee->calculate($operation);
    }
}
