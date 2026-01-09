<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Presentation\Controller\Admin;

use Phpro\SuluTranslationsBundle\Domain\Query\FetchTranslation;
use Phpro\SuluTranslationsBundle\Domain\Serializer\TranslationSerializer;
use Phpro\SuluTranslationsBundle\Presentation\Controller\Admin\FetchController;
use Phpro\SuluTranslationsBundle\Tests\Fixtures\Translations;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;

final class FetchControllerTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|FetchTranslation $fetchTranslation;
    private TranslationSerializer|ObjectProphecy $serializer;
    private FetchController $controller;

    protected function setUp(): void
    {
        $this->fetchTranslation = $this->prophesize(FetchTranslation::class);
        $this->serializer = $this->prophesize(TranslationSerializer::class);
        $this->controller = new FetchController(
            $this->fetchTranslation->reveal(),
            $this->serializer->reveal()
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
    public function it_can_fetch_translation_record(): void
    {
        $this->fetchTranslation->__invoke($id = 1)
            ->willReturn($translation = Translations::create())
            ->shouldBeCalled();
        $this->serializer->__invoke($translation)
            ->willReturn(['id' => $id])
            ->shouldBeCalled();

        $response = ($this->controller)($id);
        self::assertSame('{"id":1}', $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }
}
