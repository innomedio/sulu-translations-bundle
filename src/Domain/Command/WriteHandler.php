<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Domain\Command;

use Phpro\SuluTranslationsBundle\Domain\Events\EventDispatcher;
use Phpro\SuluTranslationsBundle\Domain\Events\Translation\TranslationCreatedEvent;
use Phpro\SuluTranslationsBundle\Domain\Events\Translation\TranslationUpdatedEvent;
use Phpro\SuluTranslationsBundle\Domain\Model\Translation;
use Phpro\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Phpro\SuluTranslationsBundle\Domain\Time\Clock;

class WriteHandler
{
    public function __construct(
        private readonly Clock $clock,
        private readonly TranslationRepository $repository,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(WriteCommand $command): void
    {
        $translation = $this->repository->findByKeyLocaleDomain($command->translationKey, $command->locale, $command->domain);

        if (null !== $translation) {
            $translation->patch($command->translationMessage, $this->clock->now());
            $this->repository->update($translation);
            $this->eventDispatcher->dispatch(new TranslationUpdatedEvent($translation));

            return;
        }

        $this->repository->create($newTranslation = Translation::create(
            $command->locale,
            $command->domain,
            $command->translationKey,
            $command->translationMessage,
            $this->clock->now(),
        ));

        $this->eventDispatcher->dispatch(new TranslationCreatedEvent($newTranslation));
    }
}
