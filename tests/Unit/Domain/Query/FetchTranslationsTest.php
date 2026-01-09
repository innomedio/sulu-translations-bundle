<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Query;

use Phpro\SuluTranslationsBundle\Domain\Model\TranslationCollection;
use Phpro\SuluTranslationsBundle\Domain\Query\FetchTranslations;
use Phpro\SuluTranslationsBundle\Domain\Query\SearchCriteria;
use Phpro\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Phpro\SuluTranslationsBundle\Tests\Fixtures\Translations;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

final class FetchTranslationsTest extends TestCase
{
    use ProphecyTrait;

    private TranslationRepository|ObjectProphecy $repository;
    private FetchTranslations $fetcher;

    protected function setUp(): void
    {
        $this->repository = $this->prophesize(TranslationRepository::class);

        $this->fetcher = new FetchTranslations(
            $this->repository->reveal(),
        );
    }

    /** @test */
    public function it_can_fetch_translations(): void
    {
        $this->repository->findByCriteria($criteria = new SearchCriteria(
            'searchValue',
            ['locale' => 'en'],
            'columnValue',
            'ASC',
            0,
            20
        ))->willReturn($collection = new TranslationCollection(Translations::create()));
        $this->repository->countByCriteria($criteria)->willReturn($count = 1);

        $result = ($this->fetcher)($criteria);

        self::assertSame($collection, $result->translationCollection());
        self::assertSame($count, $result->totalCount());
    }
}
