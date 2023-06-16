<?php

declare(strict_types=1);

namespace App\Wallet\Domain\Factory;

use App\Wallet\Domain\Entity\Operation;
use App\Wallet\Domain\Entity\OperationDate;
use App\Wallet\Domain\Entity\OperationType;
use App\Wallet\Domain\Entity\UserId;
use App\Wallet\Domain\Entity\UserType;
use Money\Currency;
use Money\Money;

class OperationFactory implements OperationFactoryInterface
{
    private array $requiredFields = [
        'operation_date',
        'user_id',
        'user_type',
        'operation_type',
        'operation_amount',
        'operation_currency',
    ];

    public function create(array $data): Operation
    {
        if (!array_diff($data, $this->requiredFields)) {
            throw new \InvalidArgumentException('The data does not contains all necessary fields');
        }

        return new Operation(
            new OperationDate($data['operation_date']),
            new UserId((int) $data['user_id']),
            new UserType($data['user_type']),
            new OperationType($data['operation_type']),
            new Money($data['operation_amount'] * 100, new Currency($data['operation_currency'])),
        );
    }
}
