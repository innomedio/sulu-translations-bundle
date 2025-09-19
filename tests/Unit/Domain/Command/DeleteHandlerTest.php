<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Command;

use Phpro\SuluTranslationsBundle\Domain\Command\DeleteCommand;
use Phpro\SuluTranslationsBundle\Domain\Command\DeleteHandler;
use Phpro\SuluTranslationsBundle\Domain\Events\EventDispatcher;
use Phpro\SuluTranslationsBundle\Domain\Events\Translation\TranslationDeletedEvent;
use Phpro\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Phpro\SuluTranslationsBundle\Domain\Time\Clock;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class DeleteHandlerTest extends TestCase
{
    use ProphecyTrait;

    private Clock|ObjectProphecy $clock;
    private TranslationRepository|ObjectProphecy $repository;
    private EventDispatcher|ObjectProphecy $eventDispatcher;
    private DeleteHandler $handler;

    protected function setUp(): void
    {
        $this->clock = $this->prophesize(Clock::class);
        $this->repository = $this->prophesize(TranslationRepository::class);
        $this->eventDispatcher = $this->prophesize(EventDispatcher::class);

        $this->handler = new DeleteHandler(
            $this->clock->reveal(),
            $this->repository->reveal(),
            $this->eventDispatcher->reveal(),
        );
    }

    /** @test */
    public function it_can_remove_a_translation_record(): void
    {
        $command = new DeleteCommand($key = 'key', $locale = 'en', $domain = 'domain');
        $this->repository->deleteByKeyLocaleDomain(
            $key,
            $locale,
            $domain
        )->shouldBeCalled();
        $this->clock->now()
            ->willReturn($now = new \DateTimeImmutable());
        $this->eventDispatcher
            ->dispatch(Argument::that(fn (TranslationDeletedEvent $event) => $event->translationKey === $key
                && $event->locale === $locale
                && $event->domain === $domain
                && $event->removedAt === $now))
            ->shouldBeCalled();

        ($this->handler)($command);
    }
}
