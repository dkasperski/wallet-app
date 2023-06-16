<?php

declare(strict_types=1);

namespace App\Wallet\Domain\Entity;

use App\Shared\Aggregate\AggregateRoot;
use App\Wallet\Application\Event\OperationCreatedEvent;
use Money\Money;

class Operation extends AggregateRoot
{
    private OperationDate $operationDate;

    private UserId $userId;

    private UserType $userType;

    private OperationType $operationType;

    private Money $money;

    private ?FeeOperation $feeOperation = null;

    public function __construct(
        OperationDate $operationDate,
        UserId $userId,
        UserType $userType,
        OperationType $operationType,
        Money $money
    ) {
        $this->operationDate = $operationDate;
        $this->userId = $userId;
        $this->userType = $userType;
        $this->operationType = $operationType;
        $this->money = $money;

        $this->recordDomainEvent(new OperationCreatedEvent($this));
    }

    public function getOperationDate(): OperationDate
    {
        return $this->operationDate;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getUserType(): UserType
    {
        return $this->userType;
    }

    public function getOperationType(): OperationType
    {
        return $this->operationType;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function getFeeOperation(): ?FeeOperation
    {
        return $this->feeOperation;
    }

    public function setFeeOperation(Money $money): void
    {
        $this->feeOperation = new FeeOperation($money);
    }

    public function toArray(): array
    {
        return [
            'operation_date' => $this->operationDate->getValue(),
            'user_id' => $this->userId->getValue(),
            'user_type' => $this->userType->getValue(),
            'operation_type' => $this->operationType->getValue(),
            'operation_amount' => $this->money->getAmount() / 100,
            'operation_currency' => $this->money->getCurrency()->getCode(),
        ];
    }
}
