<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Exception;

use Phpro\SuluTranslationsBundle\Domain\Exception\ExportFailedException;
use PHPUnit\Framework\TestCase;

final class ExportFailedExceptionTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider provideTestCases
     */
    public function it_can_create_failed_export(?\Exception $previous): void
    {
        $exception = ExportFailedException::create($previous);
        self::assertInstanceOf(ExportFailedException::class, $exception);
        self::assertEquals($previous, $exception->getPrevious());
        self::assertSame('Export failed exception', $exception->getMessage());
    }

    public function provideTestCases(): \Generator
    {
        yield 'with_previous_exception' => [new \Exception('Previous exception')];
        yield 'without_previous_exception' => [null];
    }
}
