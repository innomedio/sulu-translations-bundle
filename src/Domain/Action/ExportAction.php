<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Action;

use Phpro\SuluTranslationsBundle\Domain\Exception\ExportFailedException;

interface ExportAction
{
    /**
     * @throws ExportFailedException
     */
    public function __invoke(): string;
}
