<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Presentation\Controller\Admin;

use Phpro\SuluTranslationsBundle\Domain\Command\ExportCommand;
use Phpro\SuluTranslationsBundle\Domain\Command\ExportHandler;
use Phpro\SuluTranslationsBundle\Presentation\Controller\Admin\ExportController;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;

final class ExportControllerTest extends TestCase
{
    use ProphecyTrait;

    private ExportHandler|ObjectProphecy $handler;
    private ExportController $controller;

    protected function setUp(): void
    {
        $this->handler = $this->prophesize(ExportHandler::class);
        $this->controller = new ExportController(
            $this->handler->reveal(),
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
    public function it_can_export_translations(): void
    {
        $this->handler
            ->__invoke(new ExportCommand())
            ->willReturn('OK')
            ->shouldBeCalled();

        $response = ($this->controller)();
        self::assertSame('{"message":"OK"}', $response->getContent());
        self::assertSame(200, $response->getStatusCode());
    }
}
