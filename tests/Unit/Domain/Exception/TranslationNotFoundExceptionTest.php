<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Exception;

use Phpro\SuluTranslationsBundle\Domain\Exception\TranslationNotFoundException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class TranslationNotFoundExceptionTest extends TestCase
{
    /** @test */
    public function it_make_a_translation_not_found_exception_with_id(): void
    {
        $exception = TranslationNotFoundException::withId(1);
        self::assertInstanceOf(TranslationNotFoundException::class, $exception);
        self::assertInstanceOf(NotFoundHttpException::class, $exception);
        self::assertSame('Translation not found for ID 1.', $exception->getMessage());
    }
}
