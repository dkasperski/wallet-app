<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use Money\Money;

abstract class FeeOperationValueObject
{
    protected Money $money;

    public function __construct(Money $money)
    {
        $this->money = $money;
    }

    public function getValue(): Money
    {
        return $this->money;
    }
}
