<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Provider;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qlimix\Http\Request\ServerRequestBuilderInterface;
use Qlimix\Http\Response\ResponseEmitterInterface;
use Qlimix\Kernel\Http\Provider\Exception\ProviderException;
use Throwable;

final class PSRContainerProvider implements ProviderInterface
{
    private ContainerInterface $container;

    private string $requestHandlerId;

    public function __construct(ContainerInterface $container, string $requestHandlerId)
    {
        $this->container = $container;
        $this->requestHandlerId = $requestHandlerId;
    }

    /**
     * @inheritDoc
     */
    public function getRequestHandler(): RequestHandlerInterface
    {
        try {
            return $this->container->get($this->requestHandlerId);
        } catch (Throwable $exception) {
            throw new ProviderException('Failed to provide request handler', 0, $exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function getServerRequestBuilder(): ServerRequestBuilderInterface
    {
        try {
            return $this->container->get(ServerRequestBuilderInterface::class);
        } catch (Throwable $exception) {
            throw new ProviderException('Failed to provide request handler', 0, $exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function getResponseEmitter(): ResponseEmitterInterface
    {
        try {
            return $this->container->get(ResponseEmitterInterface::class);
        } catch (Throwable $exception) {
            throw new ProviderException('Failed to provide request handler', 0, $exception);
        }
    }
}
