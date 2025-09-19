<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Infrastructure\Symfony\Time;

use Phpro\SuluTranslationsBundle\Domain\Time\Clock;
use Symfony\Component\Clock\ClockInterface;

class UtcClock implements Clock
{
    public function __construct(private readonly ClockInterface $clock)
    {
    }

    public function now(): \DateTimeImmutable
    {
        return $this->clock->now();
    }
}
