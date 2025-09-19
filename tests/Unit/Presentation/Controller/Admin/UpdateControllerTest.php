<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Presentation\Controller\Admin;

use Phpro\SuluTranslationsBundle\Domain\Command\UpdateCommand;
use Phpro\SuluTranslationsBundle\Domain\Command\UpdateHandler;
use Phpro\SuluTranslationsBundle\Domain\Query\FetchTranslation;
use Phpro\SuluTranslationsBundle\Domain\Serializer\TranslationSerializer;
use Phpro\SuluTranslationsBundle\Presentation\Controller\Admin\UpdateController;
use Phpro\SuluTranslationsBundle\Tests\Fixtures\Translations;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;

class UpdateControllerTest extends TestCase
{
    use ProphecyTrait;

    private UpdateHandler|ObjectProphecy $handler;
    private FetchTranslation|ObjectProphecy $fetchTranslation;
    private TranslationSerializer|ObjectProphecy $serializer;
    private UpdateController $controller;

    protected function setUp(): void
    {
        $this->handler = $this->prophesize(UpdateHandler::class);
        $this->fetchTranslation = $this->prophesize(FetchTranslation::class);
        $this->serializer = $this->prophesize(TranslationSerializer::class);
        $this->controller = new UpdateController(
            $this->handler->reveal(),
            $this->fetchTranslation->reveal(),
            $this->serializer->reveal(),
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
    public function it_can_update_a_translation_value_of_a_translation_record(): void
    {
        $this->handler
            ->__invoke(new UpdateCommand($id = 1, $translationValue = 'Some updated value'))
            ->shouldBeCalled();
        $this->fetchTranslation->__invoke($id)
            ->willReturn($translation = Translations::create())
            ->shouldBeCalled();
        $this->serializer->__invoke($translation)
            ->willReturn(['id' => $id])
            ->shouldBeCalled();

        $response = ($this->controller)($id, new Request(request: ['translation' => $translationValue]));
        self::assertSame('{"id":1}', $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }
}
