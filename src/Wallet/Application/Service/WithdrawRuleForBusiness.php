<?php

declare(strict_types=1);

namespace App\Wallet\Application\Service;

use App\Wallet\Domain\Entity\Operation;
use Money\Money;

class WithdrawRuleForBusiness implements CalculateFeeInterface
{
    private const COMMISSION_VALUE = 0.005;

    public function calculate(Operation $operation): Money
    {
        return $operation->getMoney()->multiply(self::COMMISSION_VALUE);
    }
}
