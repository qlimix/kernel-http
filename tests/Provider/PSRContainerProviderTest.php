<?php declare(strict_types=1);

namespace Qlimix\Tests\Kernel\Http;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qlimix\Http\Request\ServerRequestBuilderInterface;
use Qlimix\Http\Response\ResponseEmitterInterface;
use Qlimix\Kernel\Http\Provider\Exception\ProviderException;
use Qlimix\Kernel\Http\Provider\PSRContainerProvider;

final class PSRContainerProviderTest extends TestCase
{
    private const REQUEST_HANDLER_ID = 'foo';

    /** @var MockObject */
    private $container;

    /** @var PSRContainerProvider */
    private $provider;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);

        $this->provider = new PSRContainerProvider($this->container, self::REQUEST_HANDLER_ID);
    }

    /**
     * @test
     */
    public function shouldProvideRequestHandler(): void
    {
        $requestHandler = $this->createMock(RequestHandlerInterface::class);

        $this->container->expects($this->once())
            ->method('get')
            ->with($this->callback(static function (string $requestHandlerId) {
                return self::REQUEST_HANDLER_ID === $requestHandlerId;
            }))
            ->willReturn($requestHandler);

        $this->provider->getRequestHandler();
    }

    /**
     * @test
     */
    public function shouldThrowOnRequestHandlerFailure(): void
    {
        $this->container->expects($this->once())
            ->method('get')
            ->willThrowException(new class extends Exception implements NotFoundExceptionInterface {});

        $this->expectException(ProviderException::class);

        $this->provider->getRequestHandler();
    }

    /**
     * @test
     */
    public function shouldProvideServerRequestBuilder(): void
    {
        $serverRequestBuilder = $this->createMock(ServerRequestBuilderInterface::class);

        $this->container->expects($this->once())
            ->method('get')
            ->willReturn($serverRequestBuilder);

        $this->provider->getServerRequestBuilder();
    }

    /**
     * @test
     */
    public function shouldThrowOnServerRequestBuilderFailure(): void
    {
        $this->container->expects($this->once())
            ->method('get')
            ->willThrowException(new class extends Exception implements NotFoundExceptionInterface {});

        $this->expectException(ProviderException::class);

        $this->provider->getServerRequestBuilder();
    }

    /**
     * @test
     */
    public function shouldProvideResponseEmitter(): void
    {
        $serverRequestBuilder = $this->createMock(ResponseEmitterInterface::class);

        $this->container->expects($this->once())
            ->method('get')
            ->willReturn($serverRequestBuilder);

        $this->provider->getResponseEmitter();
    }

    /**
     * @test
     */
    public function shouldThrowOnResponseEmitterFailure(): void
    {
        $this->container->expects($this->once())
            ->method('get')
            ->willThrowException(new class extends Exception implements NotFoundExceptionInterface {});

        $this->expectException(ProviderException::class);

        $this->provider->getResponseEmitter();
    }
}
