<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Presentation\Console;

use Phpro\SuluTranslationsBundle\Infrastructure\Doctrine\Schema\SetupTranslationsTable;
use Phpro\SuluTranslationsBundle\Presentation\Console\SetupTranslationsTableCommand;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Tester\CommandTester;

final class SetupTranslationsTableCommandTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|SetupTranslationsTable $schema;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $this->schema = $this->prophesize(SetupTranslationsTable::class);

        $this->commandTester = new CommandTester(
            new SetupTranslationsTableCommand(
                $this->schema->reveal(),
            )
        );
    }

    /** @test */
    public function it_can_trigger_schema_execute(): void
    {
        $this->schema->execute()
            ->shouldBeCalledOnce();

        $this->commandTester->execute([]);
        $messages = $this->commandTester->getDisplay();

        self::assertStringContainsString('Finished! The translation table was created or updated.', $messages);
    }
}
