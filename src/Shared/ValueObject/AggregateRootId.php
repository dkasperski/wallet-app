<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

abstract class AggregateRootId
{
    protected int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
