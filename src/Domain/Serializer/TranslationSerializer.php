<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Serializer;

use Phpro\SuluTranslationsBundle\Domain\Model\Translation;

class TranslationSerializer
{
    public function __invoke(Translation $translation): array
    {
        return [
            'id' => $translation->getId(),
            'translationKey' => $translation->getTranslationKey(),
            'translation' => $translation->getTranslation(),
            'domain' => $translation->getDomain(),
            'locale' => $translation->getLocale(),
        ];
    }
}
