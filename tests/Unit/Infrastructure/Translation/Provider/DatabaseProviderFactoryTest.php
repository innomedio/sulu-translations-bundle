<?php

declare(strict_types=1);

namespace Phpro\SuluTranslationsBundle\Tests\Unit\Infrastructure\Translation\Provider;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Phpro\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\DatabaseProvider;
use Phpro\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\DatabaseProviderFactory;
use Phpro\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\Loader;
use Phpro\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\Remover;
use Phpro\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\Writer;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Translation\Exception\LogicException;
use Symfony\Component\Translation\Exception\UnsupportedSchemeException;
use Symfony\Component\Translation\Provider\Dsn;
use Symfony\Component\Translation\Provider\ProviderInterface;

final class DatabaseProviderFactoryTest extends TestCase
{
    use ProphecyTrait;

    private Writer|ObjectProphecy $writer;
    private Loader|ObjectProphecy $loader;
    private Remover|ObjectProphecy $remover;
    private ManagerRegistry|ObjectProphecy $managerRegistry;
    private DatabaseProviderFactory $factory;

    protected function setUp(): void
    {
        $this->writer = $this->prophesize(Writer::class);
        $this->loader = $this->prophesize(Loader::class);
        $this->remover = $this->prophesize(Remover::class);
        $this->managerRegistry = $this->prophesize(ManagerRegistry::class);

        $this->factory = new DatabaseProviderFactory(
            $this->writer->reveal(),
            $this->loader->reveal(),
            $this->remover->reveal(),
            $this->managerRegistry->reveal(),
        );
    }

    /** @test */
    public function it_can_create_a_database_provider(): void
    {
        $connection = $this->prophesize(Connection::class);
        $this->managerRegistry->getConnection('default')->willReturn($connection->reveal())->shouldBeCalled();

        $provider = $this->factory->create(new Dsn('database://default'));
        self::assertInstanceOf(ProviderInterface::class, $provider);
        self::assertInstanceOf(DatabaseProvider::class, $provider);
        self::assertStringEndsWith('default', (string) $provider);
    }

    /** @test */
    public function it_will_throw_an_exception_with_invalid_scheme_given(): void
    {
        self::expectException(UnsupportedSchemeException::class);
        $this->factory->create(new Dsn('invalid://phpro_translations'));
    }

    /** @test */
    public function it_will_throw_an_exception_with_invalid_connection_name_is_given(): void
    {
        self::expectException(LogicException::class);
        $this->managerRegistry->getConnection('invalid-name')->willThrow(new \InvalidArgumentException('Invalid connection name'));

        $this->factory->create(new Dsn('database://invalid-name'));
    }
}
