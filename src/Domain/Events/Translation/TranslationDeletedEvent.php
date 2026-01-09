<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Events\Translation;

use Phpro\SuluTranslationsBundle\Domain\Events\DomainEvent;

final class TranslationDeletedEvent implements DomainEvent
{
    public function __construct(
        public string $translationKey,
        public string $locale,
        public string $domain,
        public \DateTimeImmutable $removedAt,
    ) {
    }
}
