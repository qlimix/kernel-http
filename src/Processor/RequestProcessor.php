<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Processor;

use Qlimix\Kernel\Http\Processor\Exception\RequestProcessorException;
use Qlimix\Kernel\Http\Provider\ProviderInterface;
use Throwable;

final class RequestProcessor implements RequestProcessorInterface
{
    private ProviderInterface $provider;

    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @inheritDoc
     */
    public function process(): void
    {
        try {
            $requestHandler = $this->provider->getRequestHandler();
            $serverRequestBuilder = $this->provider->getServerRequestBuilder();
            $responseEmitter = $this->provider->getResponseEmitter();

            $response = $requestHandler->handle($serverRequestBuilder->buildFromGlobals());

            $responseEmitter->emit($response);
        } catch (Throwable $exception) {
            throw new RequestProcessorException('failed to handle request', 0, $exception);
        }
    }
}
