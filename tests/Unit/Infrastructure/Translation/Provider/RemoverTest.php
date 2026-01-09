<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Infrastructure\Translation\Provider;

use Phpro\SuluTranslationsBundle\Domain\Command\DeleteCommand;
use Phpro\SuluTranslationsBundle\Domain\Command\DeleteHandler;
use Phpro\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\Remover;
use Phpro\SuluTranslationsBundle\Tests\Fixtures\TranslationBags;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

final class RemoverTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|DeleteHandler $handler;
    private Remover $remover;

    protected function setUp(): void
    {
        $this->handler = $this->prophesize(DeleteHandler::class);

        $this->remover = new Remover(
            $this->handler->reveal(),
        );
    }

    /** @test */
    public function it_can_remove_translations_via_delete_handler(): void
    {
        $translationBag = TranslationBags::simple();
        $this->handler->__invoke(Argument::that(fn (DeleteCommand $command) => 'app.foo' === $command->translationKey
            && 'en' === $command->locale
            && 'messages' === $command->domain))
            ->shouldBeCalledOnce();

        $this->remover->execute($translationBag);
    }
}
