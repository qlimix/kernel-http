<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Provider;

use Psr\Http\Server\RequestHandlerInterface;
use Qlimix\Http\Request\ServerRequestBuilderInterface;
use Qlimix\Http\Response\ResponseEmitterInterface;
use Qlimix\Kernel\Http\Provider\Exception\ProviderException;

interface ProviderInterface
{
    /**
     * @throws ProviderException
     */
    public function getRequestHandler(): RequestHandlerInterface;

    /**
     * @throws ProviderException
     */
    public function getServerRequestBuilder(): ServerRequestBuilderInterface;

    /**
     * @throws ProviderException
     */
    public function getResponseEmitter(): ResponseEmitterInterface;
}
