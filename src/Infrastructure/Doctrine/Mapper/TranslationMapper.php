<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Infrastructure\Doctrine\Mapper;

use Phpro\SuluTranslationsBundle\Domain\Model\DateTimeType;
use Phpro\SuluTranslationsBundle\Domain\Model\Translation;

use function Psl\Type\int;
use function Psl\Type\nullable;
use function Psl\Type\shape;
use function Psl\Type\string;

class TranslationMapper
{
    /**
     * @return array<string, mixed>
     */
    public function toDb(Translation $translation): array
    {
        return [
            'locale' => $translation->getLocale(),
            'domain' => $translation->getDomain(),
            'translation_key' => $translation->getTranslationKey(),
            'translation' => $translation->getTranslation(),
            'created_at' => $translation->getCreatedAt(),
            'updated_at' => $translation->getUpdatedAt(),
        ];
    }

    public function fromDb(array $data): Translation
    {
        $parsedData = shape([
            'phpro_translations_id' => int(),
            'phpro_translations_locale' => string(),
            'phpro_translations_domain' => string(),
            'phpro_translations_translation_key' => string(),
            'phpro_translations_translation' => string(),
            'phpro_translations_created_at' => DateTimeType::type(),
            'phpro_translations_updated_at' => nullable(DateTimeType::type()),
        ])->coerce($data);

        return Translation::load(
            $parsedData['phpro_translations_id'],
            $parsedData['phpro_translations_locale'],
            $parsedData['phpro_translations_domain'],
            $parsedData['phpro_translations_translation_key'],
            $parsedData['phpro_translations_translation'],
            $parsedData['phpro_translations_created_at'],
            $parsedData['phpro_translations_updated_at'],
        );

    }
}
