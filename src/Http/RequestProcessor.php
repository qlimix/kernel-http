<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Http;

use Qlimix\Kernel\Http\Http\Exception\RequestProcessorException;
use Throwable;

final class RequestProcessor implements RequestProcessorInterface
{
    /** @var ProviderInterface */
    private $provider;

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
