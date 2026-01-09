<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Command;

use Phpro\SuluTranslationsBundle\Domain\Action\ExportAction;
use Phpro\SuluTranslationsBundle\Domain\Events\EventDispatcher;
use Phpro\SuluTranslationsBundle\Domain\Events\Translation\TranslationsExportedEvent;
use Phpro\SuluTranslationsBundle\Domain\Time\Clock;

/**
 * @psalm-suppress ClassMustBeFinal - Mocked in tests
 */
class ExportHandler
{
    public function __construct(
        private readonly Clock $clock,
        private readonly ExportAction $exportAction,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(ExportCommand $command): string
    {
        $result = ($this->exportAction)();
        $this->eventDispatcher->dispatch(new TranslationsExportedEvent($result, $this->clock->now()));

        return $result;
    }
}
