<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Presentation\Controller\Admin;

use Phpro\SuluTranslationsBundle\Domain\Model\TranslationCollection;
use Phpro\SuluTranslationsBundle\Domain\Model\TranslationList;
use Phpro\SuluTranslationsBundle\Domain\Query\FetchTranslations;
use Phpro\SuluTranslationsBundle\Domain\Query\SearchCriteria;
use Phpro\SuluTranslationsBundle\Presentation\Controller\Admin\ListController;
use Phpro\SuluTranslationsBundle\Tests\Fixtures\Translations;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sulu\Component\Rest\ListBuilder\ListRestHelperInterface;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

final class ListControllerTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|SerializerInterface $serializer;
    private ObjectProphecy|ListRestHelperInterface $listRestHelper;
    private FetchTranslations|ObjectProphecy $fetchTranslations;
    private ListController $controller;

    protected function setUp(): void
    {
        $this->serializer = $this->prophesize(SerializerInterface::class);
        $this->listRestHelper = $this->prophesize(ListRestHelperInterface::class);
        $this->fetchTranslations = $this->prophesize(FetchTranslations::class);

        $this->controller = new ListController(
            $this->serializer->reveal(),
            $this->listRestHelper->reveal(),
            $this->fetchTranslations->reveal(),
        );
    }

    /** @test */
    public function it_is_a_secured_controller(): void
    {
        self::assertInstanceOf(SecuredControllerInterface::class, $this->controller);
        self::assertSame('phpro_translations', $this->controller->getSecurityContext());
        self::assertSame('en', $this->controller->getLocale(new Request()));
    }

    /** @test */
    public function it_can_fetch_a_paginated_list(): void
    {
        $this->listRestHelper->getSortColumn()->willReturn(null);
        $this->listRestHelper->getSortOrder()->willReturn(null);
        $this->listRestHelper->getSearchPattern()->willReturn(null);
        $this->listRestHelper->getFilter()->willReturn([]);
        $this->listRestHelper->getPage()->willReturn(1);
        $this->listRestHelper->getLimit()->willReturn($limit = 2);
        $this->listRestHelper->getOffset()->willReturn($offset = 0);

        $this->fetchTranslations->__invoke(new SearchCriteria(
            '',
            [
                'locale' => null,
                'domain' => null,
                'translationKey' => null,
            ],
            null,
            null,
            $offset,
            $limit
        ))->willReturn(
            new TranslationList(
                new TranslationCollection(
                    Translations::create('Foo'),
                    Translations::create('Bar'),
                ),
                2
            )
        )->shouldBeCalledOnce();

        $this->serializer->serialize(Argument::type('array'), 'json')
            ->willReturn($serializedData = '{"_embedded": {"phpro_translations": []}, "limit": 10, "total": 2, "page": 1, "pages": 1}');

        $response = ($this->controller)();

        self::assertSame($serializedData, $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }

    /** @test */
    public function it_can_fetch_a_paginated_filtered_and_sorted_list(): void
    {
        $this->listRestHelper->getSortColumn()->willReturn($sortColumn = 'id');
        $this->listRestHelper->getSortOrder()->willReturn($sortOrder = 'ASC');
        $this->listRestHelper->getSearchPattern()->willReturn($searchPattern = 'Foo');
        $this->listRestHelper->getFilter()->willReturn([
            'locale' => ['eq' => 'nl'],
            'domain' => ['eq' => 'messages'],
            'translationKey' => ['eq' => 'foo'],
        ]);
        $this->listRestHelper->getPage()->willReturn(1);
        $this->listRestHelper->getLimit()->willReturn($limit = 2);
        $this->listRestHelper->getOffset()->willReturn($offset = 0);

        $this->fetchTranslations->__invoke(new SearchCriteria(
            $searchPattern,
            [
                'locale' => 'nl',
                'domain' => 'messages',
                'translationKey' => 'foo',
            ],
            $sortColumn,
            $sortOrder,
            $offset,
            $limit
        ))->willReturn(
            new TranslationList(
                new TranslationCollection(
                    Translations::create('Foo'),
                ),
                1
            )
        )->shouldBeCalledOnce();

        $this->serializer->serialize(Argument::type('array'), 'json')
            ->willReturn($serializedData = '{"_embedded": {"phpro_translations": []}, "limit": 10, "total": 1, "page": 1, "pages": 1}');

        $response = ($this->controller)();

        self::assertSame($serializedData, $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }
}
