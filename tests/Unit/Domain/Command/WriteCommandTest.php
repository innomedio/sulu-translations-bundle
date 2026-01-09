<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Command;

use Phpro\SuluTranslationsBundle\Domain\Command\WriteCommand;
use PHPUnit\Framework\TestCase;

final class WriteCommandTest extends TestCase
{
    /** @test */
    public function it_can_create_a_command(): void
    {
        $command = new WriteCommand(
            $key = 'key',
            $locale = 'en',
            $domain = 'domain',
            $translationValue = 'translation'
        );

        self::assertInstanceOf(WriteCommand::class, $command);
        self::assertSame($key, $command->translationKey);
        self::assertSame($locale, $command->locale);
        self::assertSame($domain, $command->domain);
        self::assertSame($translationValue, $command->translationMessage);
    }
}
