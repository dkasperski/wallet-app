<?php

declare(strict_types=1);

namespace App\Wallet\Application\EventSubscriber;

use App\Wallet\Application\Event\OperationCreatedEvent;
use App\Wallet\Application\Model\CalculateFeeCommand;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class OnOperationCreatedEventSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OperationCreatedEvent::class => 'calculateFee',
        ];
    }

    public function calculateFee(OperationCreatedEvent $event): void
    {
        $this->messageBus->dispatch(new CalculateFeeCommand($event->getOperation()));
    }
}
