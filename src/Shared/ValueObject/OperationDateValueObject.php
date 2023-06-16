<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

abstract class OperationDateValueObject
{
    protected string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValidDate($value);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    protected function ensureIsValidDate(string $date): void
    {
        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date)) {
            throw new \InvalidArgumentException(sprintf('The operation date <%s> is not valid', $date));
        }
    }
}
