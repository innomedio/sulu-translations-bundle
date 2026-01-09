<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Command;

use Phpro\SuluTranslationsBundle\Domain\Events\EventDispatcher;
use Phpro\SuluTranslationsBundle\Domain\Events\Translation\TranslationUpdatedEvent;
use Phpro\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Phpro\SuluTranslationsBundle\Domain\Time\Clock;

/**
 * @psalm-suppress ClassMustBeFinal - Mocked in tests
 */
class UpdateHandler
{
    public function __construct(
        private readonly Clock $clock,
        private readonly TranslationRepository $repository,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(UpdateCommand $command): void
    {
        $translation = $this->repository->findById($command->id);
        $this->repository->update(
            $translation->patch(
                $command->translationMessage,
                $this->clock->now()
            )
        );

        $this->eventDispatcher->dispatch(new TranslationUpdatedEvent($translation));
    }
}
