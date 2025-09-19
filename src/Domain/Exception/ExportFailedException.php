<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Exception;

final class ExportFailedException extends \RuntimeException
{
    public static function create(?\Throwable $previous): self
    {
        return new self('Export failed exception', previous: $previous);
    }
}
