<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Time;

interface Clock
{
    public function now(): \DateTimeImmutable;
}
