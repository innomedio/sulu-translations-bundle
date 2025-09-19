<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Events\Translation;

use Phpro\SuluTranslationsBundle\Domain\Events\DomainEvent;

class TranslationsExportedEvent implements DomainEvent
{
    public function __construct(
        public readonly string $result,
        public readonly \DateTimeImmutable $exportedAt,
    ) {
    }
}
