<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Events;

interface EventDispatcher
{
    public function dispatch(DomainEvent $event): DomainEvent;
}
