<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Events\Translation;

use Phpro\SuluTranslationsBundle\Domain\Events\Translation\TranslationUpdatedEvent;
use Phpro\SuluTranslationsBundle\Tests\Fixtures\Translations;
use PHPUnit\Framework\TestCase;

class TranslationUpdatedEventTest extends TestCase
{
    /** @test */
    public function it_can_create_an_event(): void
    {
        $event = new TranslationUpdatedEvent($translation = Translations::create());

        self::assertInstanceOf(TranslationUpdatedEvent::class, $event);
        self::assertSame($translation, $event->translation);
    }
}
