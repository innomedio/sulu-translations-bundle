<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Command;

use Phpro\SuluTranslationsBundle\Domain\Command\UpdateCommand;
use PHPUnit\Framework\TestCase;

final class UpdateCommandTest extends TestCase
{
    /** @test */
    public function it_can_create_a_command(): void
    {
        $id = 1;
        $translationValue = 'translation';

        $command = new UpdateCommand($id, $translationValue);

        self::assertInstanceOf(UpdateCommand::class, $command);
        self::assertSame($id, $command->id);
        self::assertSame($translationValue, $command->translationMessage);
    }
}
