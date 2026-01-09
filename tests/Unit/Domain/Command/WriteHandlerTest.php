<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Command;

use Phpro\SuluTranslationsBundle\Domain\Command\WriteCommand;
use Phpro\SuluTranslationsBundle\Domain\Command\WriteHandler;
use Phpro\SuluTranslationsBundle\Domain\Events\EventDispatcher;
use Phpro\SuluTranslationsBundle\Domain\Events\Translation\TranslationCreatedEvent;
use Phpro\SuluTranslationsBundle\Domain\Events\Translation\TranslationUpdatedEvent;
use Phpro\SuluTranslationsBundle\Domain\Model\Translation;
use Phpro\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Phpro\SuluTranslationsBundle\Domain\Time\Clock;
use Phpro\SuluTranslationsBundle\Tests\Fixtures\Translations;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

final class WriteHandlerTest extends TestCase
{
    use ProphecyTrait;

    private TranslationRepository|ObjectProphecy $repository;
    private Clock|ObjectProphecy $clock;
    private EventDispatcher|ObjectProphecy $eventDispatcher;
    private WriteHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->prophesize(TranslationRepository::class);
        $this->clock = $this->prophesize(Clock::class);
        $this->eventDispatcher = $this->prophesize(EventDispatcher::class);

        $this->handler = new WriteHandler(
            $this->clock->reveal(),
            $this->repository->reveal(),
            $this->eventDispatcher->reveal(),
        );
    }

    /** @test */
    public function it_can_create_a_new_translation_record(): void
    {
        $command = new WriteCommand(
            $key = 'key',
            $locale = 'en',
            $domain = 'domain',
            $translationValue = 'translation'
        );
        $this->clock->now()
            ->willReturn($now = new \DateTimeImmutable());
        $this->repository->findByKeyLocaleDomain($key, $locale, $domain)
            ->willReturn(null);

        $this->repository->create(Argument::that(fn (Translation $translation) => $translation->getTranslation() === $translationValue
            && $translation->getTranslationKey() === $key
            && $translation->getLocale() === $locale
            && $translation->getDomain() === $domain
            && $translation->getCreatedAt() === $now
            && null === $translation->getUpdatedAt()
        ))->shouldBeCalled();
        $this->eventDispatcher
            ->dispatch(Argument::that(fn (TranslationCreatedEvent $event) => $event->translation->getTranslation() === $translationValue
                && $event->translation->getTranslationKey() === $key
                && $event->translation->getLocale() === $locale
                && $event->translation->getDomain() === $domain
                && $event->translation->getCreatedAt() === $now
                && null === $event->translation->getUpdatedAt()
            ))->shouldBeCalled();

        ($this->handler)($command);
    }

    /** @test */
    public function it_can_update_an_existing_translation_record(): void
    {
        $command = new WriteCommand(
            $key = 'key',
            $locale = 'en',
            $domain = 'domain',
            $translationValue = 'translation'
        );
        $this->clock->now()
            ->willReturn($now = new \DateTimeImmutable());
        $this->repository->findByKeyLocaleDomain($key, $locale, $domain)
            ->willReturn($exitingTranslation = Translations::create());

        $this->repository->update(Argument::that(fn (Translation $translation) => $translation->getTranslation() === $translationValue
            && $translation->getCreatedAt() === $exitingTranslation->getCreatedAt()
            && $translation->getUpdatedAt() === $now
        ))->shouldBeCalled();
        $this->eventDispatcher
            ->dispatch(Argument::that(fn (TranslationUpdatedEvent $event) => $event->translation->getTranslation() === $translationValue
                && $event->translation->getCreatedAt() === $exitingTranslation->getCreatedAt()
                && $event->translation->getUpdatedAt() === $now
            ))->shouldBeCalled();

        ($this->handler)($command);
    }
}
