<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

abstract class OperationTypeValueObject
{
    protected string $value;

    public const DEPOSIT_TYPE = 'deposit';

    public const WITHDRAW_TYPE = 'withdraw';

    public const TYPES = [self::DEPOSIT_TYPE, self::WITHDRAW_TYPE];

    public function __construct(string $value)
    {
        $this->ensureIsValidType($value);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    protected function ensureIsValidType(string $type): void
    {
        if (false === in_array($type, self::TYPES)) {
            throw new \InvalidArgumentException(sprintf('The operation type <%s> is not valid', $type));
        }
    }
}
