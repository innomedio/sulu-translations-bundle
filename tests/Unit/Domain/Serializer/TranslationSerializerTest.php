<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Serializer;

use Phpro\SuluTranslationsBundle\Domain\Serializer\TranslationSerializer;
use Phpro\SuluTranslationsBundle\Tests\Fixtures\Translations;
use PHPUnit\Framework\TestCase;

final class TranslationSerializerTest extends TestCase
{
    /** @test */
    public function it_can_serialize_a_translation(): void
    {
        $translation = Translations::withId($id = 1, Translations::create());
        $serializer = new TranslationSerializer();

        self::assertSame(
            [
                'id' => $translation->getId(),
                'translationKey' => $translation->getTranslationKey(),
                'translation' => $translation->getTranslation(),
                'domain' => $translation->getDomain(),
                'locale' => $translation->getLocale(),
            ],
            ($serializer)($translation)
        );
    }
}
