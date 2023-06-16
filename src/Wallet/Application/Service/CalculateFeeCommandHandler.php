<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Service;

use App\Wallet\Application\Model\CalculateFeeCommand;
use App\Wallet\Application\Service\CalculateFeeService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CalculateFeeCommandHandler
{
    private CalculateFeeService $calculateFeeService;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        CalculateFeeService $calculateFeeService,
        EventDispatcherInterface $eventDispatcher,
    ) {
        $this->calculateFeeService = $calculateFeeService;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(CalculateFeeCommand $calculateFeeCommand): void
    {
        $operation = $calculateFeeCommand->getOperation();
        $calculatedFee = $this->calculateFeeService->calculate($calculateFeeCommand->getOperation());
        $operation->setFeeOperation($calculatedFee);

        foreach ($operation->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }
    }
}
