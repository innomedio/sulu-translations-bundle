<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Command;

use Phpro\SuluTranslationsBundle\Domain\Command\ExportCommand;
use PHPUnit\Framework\TestCase;

class ExportCommandTest extends TestCase
{
    /** @test */
    public function it_can_create_a_command(): void
    {
        $command = new ExportCommand();

        self::assertInstanceOf(ExportCommand::class, $command);
    }
}
