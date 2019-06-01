<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Http;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qlimix\Http\Request\ServerRequestBuilderInterface;
use Qlimix\Http\Response\ResponseEmitterInterface;

final class PSRContainerProvider implements ProviderInterface
{
    /** @var ContainerInterface */
    private $container;

    /** @var string */
    private $requestHandlerId;

    public function __construct(ContainerInterface $container, string $requestHandlerId)
    {
        $this->container = $container;
        $this->requestHandlerId = $requestHandlerId;
    }

    public function getRequestHandler(): RequestHandlerInterface
    {
        return $this->container->get($this->requestHandlerId);
    }

    public function getServerRequestBuilder(): ServerRequestBuilderInterface
    {
        return $this->container->get(ServerRequestBuilderInterface::class);
    }

    public function getResponseEmitter(): ResponseEmitterInterface
    {
        return $this->container->get(ResponseEmitterInterface::class);
    }
}
