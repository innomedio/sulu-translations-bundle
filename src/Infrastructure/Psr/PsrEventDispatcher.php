<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Infrastructure\Psr;

use Phpro\SuluTranslationsBundle\Domain\Events\DomainEvent;
use Phpro\SuluTranslationsBundle\Domain\Events\EventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;

use function Psl\Type\instance_of;

final class PsrEventDispatcher implements EventDispatcher
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function dispatch(DomainEvent $event): DomainEvent
    {
        $dispatchedEvent = $this->eventDispatcher->dispatch($event);

        return instance_of(DomainEvent::class)->assert($dispatchedEvent);
    }
}
