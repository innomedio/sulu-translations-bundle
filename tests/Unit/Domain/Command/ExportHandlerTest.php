<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Command;

use Phpro\SuluTranslationsBundle\Domain\Action\ExportAction;
use Phpro\SuluTranslationsBundle\Domain\Command\ExportCommand;
use Phpro\SuluTranslationsBundle\Domain\Command\ExportHandler;
use Phpro\SuluTranslationsBundle\Domain\Events\EventDispatcher;
use Phpro\SuluTranslationsBundle\Domain\Events\Translation\TranslationsExportedEvent;
use Phpro\SuluTranslationsBundle\Domain\Time\Clock;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ExportHandlerTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|Clock $clock;
    private ObjectProphecy|ExportAction $exportAction;
    private ObjectProphecy|EventDispatcher $eventDispatcher;
    private ExportHandler $exportHandler;

    protected function setUp(): void
    {
        $this->clock = $this->prophesize(Clock::class);
        $this->exportAction = $this->prophesize(ExportAction::class);
        $this->eventDispatcher = $this->prophesize(EventDispatcher::class);

        $this->exportHandler = new ExportHandler(
            $this->clock->reveal(),
            $this->exportAction->reveal(),
            $this->eventDispatcher->reveal()
        );
    }

    /** @test */
    public function it_can_export_all_database_translations(): void
    {

        $command = new ExportCommand();
        $this->clock->now()
            ->willReturn($now = new \DateTimeImmutable());
        $this->exportAction
            ->__invoke()
            ->willReturn($exportResult = 'Export result');
        $this->eventDispatcher
            ->dispatch(Argument::that(fn (TranslationsExportedEvent $event) => $event->result === $exportResult && $event->exportedAt === $now))
            ->shouldBeCalled();

        $result = $this->exportHandler->__invoke($command);
        self::assertSame($exportResult, $result);
    }
}
