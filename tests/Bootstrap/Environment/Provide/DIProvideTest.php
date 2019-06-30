<?php declare(strict_types=1);

namespace Qlimix\Tests\Kernel\Http\Bootstrap\Environment\Provide;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Qlimix\DependencyContainer\Exception\ProviderException;
use Qlimix\DependencyContainer\ProviderInterface;
use Qlimix\DependencyContainer\Registry\ProviderCollectionInterface;
use Qlimix\DependencyContainer\RegistryInterface;
use Qlimix\Kernel\Http\Bootstrap\Dependencies\Provide\DIProvide;
use Qlimix\Kernel\Http\Bootstrap\Dependencies\Provide\Exception\ProvideException;

final class DIProvideTest extends TestCase
{
    /** @var MockObject */
    private $registry;

    /** @var MockObject */
    private $collection;

    /** @var DIProvide */
    private $provide;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(RegistryInterface::class);
        $this->collection = $this->createMock(ProviderCollectionInterface::class);

        $this->provide = new DIProvide($this->registry, $this->collection);
    }

    /**
     * @test
     */
    public function shouldProvide(): void
    {
        $this->collection->expects($this->once())
            ->method('getProviders');

        $this->provide->provide();
    }

    /**
     * @test
     */
    public function shouldProvideWithMultipleProviders(): void
    {
        $firstProvider = $this->createMock(ProviderInterface::class);

        $firstProvider->expects($this->once())
            ->method('provide');

        $secondProvider = $this->createMock(ProviderInterface::class);

        $secondProvider->expects($this->once())
            ->method('provide');

        $thirdProvider = $this->createMock(ProviderInterface::class);

        $thirdProvider->expects($this->once())
            ->method('provide');

        $providers = [
            $firstProvider,
            $secondProvider,
            $thirdProvider
        ];

        $this->collection->expects($this->once())
            ->method('getProviders')
            ->willReturn($providers);

        $this->provide->provide();
    }

    /**
     * @test
     */
    public function shouldThrowOnProviderFailure(): void
    {
        $failingProvider = $this->createMock(ProviderInterface::class);

        $failingProvider->expects($this->once())
            ->method('provide')
            ->willThrowException(new ProviderException());

        $providers = [
            $this->createMock(ProviderInterface::class),
            $this->createMock(ProviderInterface::class),
            $failingProvider
        ];

        $this->collection->expects($this->once())
            ->method('getProviders')
            ->willReturn($providers);

        $this->expectException(ProvideException::class);

        $this->provide->provide();
    }
}
