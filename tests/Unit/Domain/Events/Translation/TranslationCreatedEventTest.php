<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Events\Translation;

use Phpro\SuluTranslationsBundle\Domain\Events\Translation\TranslationCreatedEvent;
use Phpro\SuluTranslationsBundle\Tests\Fixtures\Translations;
use PHPUnit\Framework\TestCase;

class TranslationCreatedEventTest extends TestCase
{
    /** @test */
    public function it_can_create_an_event(): void
    {
        $event = new TranslationCreatedEvent($translation = Translations::create());

        self::assertInstanceOf(TranslationCreatedEvent::class, $event);
        self::assertSame($translation, $event->translation);
    }
}
