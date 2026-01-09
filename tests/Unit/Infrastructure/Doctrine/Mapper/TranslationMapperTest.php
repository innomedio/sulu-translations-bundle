<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Infrastructure\Doctrine\Mapper;

use Phpro\SuluTranslationsBundle\Domain\Model\Translation;
use Phpro\SuluTranslationsBundle\Infrastructure\Doctrine\Mapper\TranslationMapper;
use PHPUnit\Framework\TestCase;

final class TranslationMapperTest extends TestCase
{
    /** @test */
    public function it_can_map_to_db(): void
    {
        $translation = Translation::create(
            $locale = 'en',
            $domain = 'messages',
            $key = 'app.foo.bar',
            $translationValue = 'Foo Bar Value',
            $createdAt = new \DateTimeImmutable(),
        );

        $translationMapper = new TranslationMapper();
        $mappedTranslation = $translationMapper->toDb($translation);

        self::assertSame($locale, $mappedTranslation['locale']);
        self::assertSame($domain, $mappedTranslation['domain']);
        self::assertSame($key, $mappedTranslation['translation_key']);
        self::assertSame($translationValue, $mappedTranslation['translation']);
        self::assertSame($createdAt, $mappedTranslation['created_at']);
        self::assertNull($mappedTranslation['updated_at']);
    }

    /** @test */
    public function it_can_map_from_db(): void
    {
        $data = [
            'phpro_translations_id' => $id = 1,
            'phpro_translations_locale' => $locale = 'en',
            'phpro_translations_domain' => $domain = 'messages',
            'phpro_translations_translation_key' => $key = 'app.foo.bar',
            'phpro_translations_translation' => $translationValue = 'Foo Bar Value',
            'phpro_translations_created_at' => $createdAt = '2021-01-01 00:00:00',
            'phpro_translations_updated_at' => $updatedAt = '2021-01-01 10:00:00',
        ];

        $translationMapper = new TranslationMapper();
        $translation = $translationMapper->fromDb($data);

        self::assertSame($id, $translation->getId());
        self::assertSame($locale, $translation->getLocale());
        self::assertSame($domain, $translation->getDomain());
        self::assertSame($key, $translation->getTranslationKey());
        self::assertSame($translationValue, $translation->getTranslation());
        self::assertSame($createdAt, $translation->getCreatedAt()->format('Y-m-d H:i:s'));
        self::assertSame($updatedAt, $translation->getUpdatedAt()->format('Y-m-d H:i:s'));
    }
}
