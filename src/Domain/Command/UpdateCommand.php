<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Command;

class UpdateCommand
{
    public function __construct(
        public int $id,
        public string $translationMessage,
    ) {
    }
}
