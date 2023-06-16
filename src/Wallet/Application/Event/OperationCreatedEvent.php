<?php

declare(strict_types=1);

namespace App\Wallet\Application\Event;

use App\Shared\Event\DomainEventInterface;
use App\Wallet\Domain\Entity\Operation;
use Symfony\Contracts\EventDispatcher\Event;

final class OperationCreatedEvent extends Event implements DomainEventInterface
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
