<?php

declare(strict_types=1);

namespace App\Wallet\Application\Service;

use App\Wallet\Domain\Entity\Operation;
use App\Wallet\Domain\Factory\OperationFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class CreateOperationService
{
    private OperationFactoryInterface $operationFactory;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        OperationFactoryInterface $operationFactory,
        EventDispatcherInterface $eventDispatcher,
    ) {
        $this->operationFactory = $operationFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(array $data): Operation
    {
        $operation = $this->operationFactory->create($data);

        foreach ($operation->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }

        return $operation;
    }
}
