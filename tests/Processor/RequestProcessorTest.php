<?php declare(strict_types=1);

namespace Qlimix\Tests\Kernel\Http;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Qlimix\Http\Request\ServerRequestBuilderInterface;
use Qlimix\Http\Response\ResponseEmitterInterface;
use Qlimix\Kernel\Http\Processor\Exception\RequestProcessorException;
use Qlimix\Kernel\Http\Processor\RequestProcessor;
use Qlimix\Kernel\Http\Provider\Exception\ProviderException;
use Qlimix\Kernel\Http\Provider\ProviderInterface;

final class RequestProcessorTest extends TestCase
{
    /** @var MockObject */
    private $provider;

    /** @var RequestProcessor */
    private $processor;

    /** @var MockObject */
    private $requestHandler;

    /** @var MockObject */
    private $serverRequestBuilder;

    /** @var MockObject */
    private $responseEmitter;

    protected function setUp(): void
    {
        $this->provider = $this->createMock(ProviderInterface::class);

        $this->processor = new RequestProcessor($this->provider);

        $this->requestHandler = $this->createMock(RequestHandlerInterface::class);
        $this->serverRequestBuilder = $this->createMock(ServerRequestBuilderInterface::class);
        $this->responseEmitter = $this->createMock(ResponseEmitterInterface::class);
    }

    /**
     * @test
     */
    public function shouldProcess(): void
    {
        $this->requestHandler->expects($this->once())
            ->method('handle');

        $this->serverRequestBuilder->expects($this->once())
            ->method('buildFromGlobals');

        $this->responseEmitter->expects($this->once())
            ->method('emit');

        $this->provider->expects($this->once())
            ->method('getRequestHandler')
            ->willReturn($this->requestHandler);

        $this->provider->expects($this->once())
            ->method('getServerRequestBuilder')
            ->willReturn($this->serverRequestBuilder);

        $this->provider->expects($this->once())
            ->method('getResponseEmitter')
            ->willReturn($this->responseEmitter);

        $this->processor->process();
    }

    /**
     * @test
     */
    public function shouldThrowOnProviderFailure(): void
    {
        $this->provider->expects($this->once())
            ->method('getRequestHandler')
            ->willThrowException(new ProviderException());

        $this->expectException(RequestProcessorException::class);

        $this->processor->process();
    }
}
