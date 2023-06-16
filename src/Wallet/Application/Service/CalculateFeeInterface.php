<?php

declare(strict_types=1);

namespace App\Wallet\Application\Service;

use App\Wallet\Domain\Entity\Operation;
use Money\Money;

interface CalculateFeeInterface
{
    public function calculate(Operation $operation): Money;
}
