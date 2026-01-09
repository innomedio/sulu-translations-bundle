<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Model;

use Phpro\SuluTranslationsBundle\Domain\Model\TranslationCollection;
use Phpro\SuluTranslationsBundle\Tests\Fixtures\Translations;
use PHPUnit\Framework\TestCase;

final class TranslationCollectionTest extends TestCase
{
    private TranslationCollection $collection;

    protected function setUp(): void
    {
        $this->collection = new TranslationCollection(
            Translations::create('Foo 1'),
            Translations::create('Foo 2'),
        );
    }

    /** @test */
    public function it_is_iterable(): void
    {
        foreach ($this->collection as $translation) {
            self::assertStringStartsWith('Foo', $translation->getTranslationKey());
        }
    }

    /** @test */
    public function it_has_a_count(): void
    {
        self::assertSame(2, $this->collection->count());
    }
}
