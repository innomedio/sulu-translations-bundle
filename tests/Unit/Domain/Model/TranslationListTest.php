<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Model;

use Phpro\SuluTranslationsBundle\Domain\Model\TranslationCollection;
use Phpro\SuluTranslationsBundle\Domain\Model\TranslationList;
use Phpro\SuluTranslationsBundle\Tests\Fixtures\Translations;
use PHPUnit\Framework\TestCase;

class TranslationListTest extends TestCase
{
    private TranslationCollection $collection;
    private TranslationList $list;

    protected function setUp(): void
    {
        $this->list = new TranslationList(
            $this->collection = new TranslationCollection(
                Translations::create('Foo')
            ),
            1
        );
    }

    /** @test */
    public function it_has_collection(): void
    {
        self::assertSame($this->collection, $this->list->translationCollection());
        self::assertCount(1, $this->list->translationCollection());
    }

    /** @test */
    public function it_has_a_total_count(): void
    {
        self::assertSame(1, $this->list->totalCount());
    }
}
