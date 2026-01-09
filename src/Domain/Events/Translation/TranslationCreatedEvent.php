<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Events\Translation;

use Phpro\SuluTranslationsBundle\Domain\Events\DomainEvent;
use Phpro\SuluTranslationsBundle\Domain\Model\Translation;

final class TranslationCreatedEvent implements DomainEvent
{
    public function __construct(
        public readonly Translation $translation,
    ) {
    }
}
