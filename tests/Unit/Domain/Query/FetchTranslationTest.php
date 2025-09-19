<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Domain\Query;

use Phpro\SuluTranslationsBundle\Domain\Query\FetchTranslation;
use Phpro\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Phpro\SuluTranslationsBundle\Tests\Fixtures\Translations;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class FetchTranslationTest extends TestCase
{
    use ProphecyTrait;

    private TranslationRepository|ObjectProphecy $repository;
    private FetchTranslation $fetcher;

    protected function setUp(): void
    {
        $this->repository = $this->prophesize(TranslationRepository::class);

        $this->fetcher = new FetchTranslation(
            $this->repository->reveal(),
        );
    }

    /** @test */
    public function it_can_fetch_a_translation(): void
    {
        $this->repository->findById($id = 1)
            ->willReturn($translation = Translations::create());

        self::assertSame($translation, ($this->fetcher)($id));
    }
}
