<?php

declare(strict_types=1);

namespace App\Wallet\Domain\Factory;

use App\Wallet\Domain\Entity\Operation;

interface OperationFactoryInterface
{
    public function create(array $data): Operation;
}
