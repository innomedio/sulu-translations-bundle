<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Command;

use Phpro\SuluTranslationsBundle\Domain\Events\EventDispatcher;
use Phpro\SuluTranslationsBundle\Domain\Events\Translation\TranslationDeletedEvent;
use Phpro\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Phpro\SuluTranslationsBundle\Domain\Time\Clock;

/**
 * @psalm-suppress ClassMustBeFinal - Mocked in tests
 */
class DeleteHandler
{
    public function __construct(
        private readonly Clock $clock,
        private readonly TranslationRepository $repository,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(DeleteCommand $command): void
    {
        $this->repository->deleteByKeyLocaleDomain(
            $command->translationKey,
            $command->locale,
            $command->domain
        );

        $this->eventDispatcher->dispatch(
            new TranslationDeletedEvent(
                $command->translationKey,
                $command->locale,
                $command->domain,
                $this->clock->now(),
            )
        );
    }
}
