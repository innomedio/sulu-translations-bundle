<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Infrastructure\Time;

use Phpro\SuluTranslationsBundle\Infrastructure\Symfony\Time\UtcClock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\MockClock;

class UtcClockTest extends TestCase
{
    /** @test */
    public function it_can_return_the_current_datetime(): void
    {
        $clock = new UtcClock(
            new MockClock($now = new \DateTimeImmutable())
        );

        self::assertEquals($now, $clock->now());
    }
}
