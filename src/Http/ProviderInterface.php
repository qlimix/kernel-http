<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Http;

use Psr\Http\Server\RequestHandlerInterface;
use Qlimix\Http\Request\ServerRequestBuilderInterface;
use Qlimix\Http\Response\ResponseEmitterInterface;

interface ProviderInterface
{
    public function getRequestHandler(): RequestHandlerInterface;

    public function getServerRequestBuilder(): ServerRequestBuilderInterface;

    public function getResponseEmitter(): ResponseEmitterInterface;
}
