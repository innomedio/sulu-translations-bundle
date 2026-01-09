<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Command;

use Phpro\SuluTranslationsBundle\Domain\Command\UpdateCommand;
use Phpro\SuluTranslationsBundle\Domain\Command\UpdateHandler;
use Phpro\SuluTranslationsBundle\Domain\Events\EventDispatcher;
use Phpro\SuluTranslationsBundle\Domain\Events\Translation\TranslationUpdatedEvent;
use Phpro\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Phpro\SuluTranslationsBundle\Domain\Time\Clock;
use Phpro\SuluTranslationsBundle\Tests\Fixtures\Translations;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

final class UpdateHandlerTest extends TestCase
{
    use ProphecyTrait;

    private TranslationRepository|ObjectProphecy $repository;
    private Clock|ObjectProphecy $clock;
    private EventDispatcher|ObjectProphecy $eventDispatcher;
    private UpdateHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->prophesize(TranslationRepository::class);
        $this->clock = $this->prophesize(Clock::class);
        $this->eventDispatcher = $this->prophesize(EventDispatcher::class);

        $this->handler = new UpdateHandler(
            $this->clock->reveal(),
            $this->repository->reveal(),
            $this->eventDispatcher->reveal(),
        );
    }

    /** @test */
    public function it_can_update_a_translation_record(): void
    {
        $translationValue = 'new translation';
        $this->repository->findById($id = 1)
            ->willReturn($translation = Translations::create());
        $this->clock->now()
            ->willReturn($updatedAt = new \DateTimeImmutable());
        $this->repository->update($translation)
            ->shouldBeCalled();
        $this->eventDispatcher
            ->dispatch(Argument::that(fn (TranslationUpdatedEvent $event) => $event->translation === $translation))
            ->shouldBeCalled();

        ($this->handler)(new UpdateCommand($id, $translationValue));
        self::assertSame($updatedAt, $translation->getUpdatedAt());
        self::assertSame($translationValue, $translation->getTranslation());
    }
}
