<?php

declare(strict_types=1);

namespace App\Wallet\Application\Model;

use App\Wallet\Domain\Entity\Operation;

final class CalculateFeeCommand
{
    private Operation $operation;

    public function __construct(Operation $operation)
    {
        $this->operation = $operation;
    }

    public function getOperation(): Operation
    {
        return $this->operation;
    }
}
