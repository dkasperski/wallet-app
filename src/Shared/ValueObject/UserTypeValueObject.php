<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

abstract class UserTypeValueObject
{
    protected string $value;

    public const PRIVATE_TYPE = 'private';

    public const BUSINESS_TYPE = 'business';

    public const TYPES = [self::PRIVATE_TYPE, self::BUSINESS_TYPE];

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
            throw new \InvalidArgumentException(sprintf('The user type <%s> is not valid', $type));
        }
    }
}
