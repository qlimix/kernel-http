<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http;

use Dotenv\Dotenv;
use Psr\Http\Server\RequestHandlerInterface;
use Qlimix\DependencyContainer\Exception\ProviderException;
use Qlimix\DependencyContainer\Registry\ProviderAssemblerInterface;
use Qlimix\DependencyContainer\Registry\ProviderRegistry;
use Qlimix\DependencyContainer\RegistryInterface;
use Qlimix\Environment\Environment;
use Qlimix\Http\Request\ServerRequestBuilderInterface;
use Qlimix\Http\Response\ResponseEmitterInterface;
use Qlimix\Kernel\Exception\KernelException;
use Qlimix\Kernel\KernelInterface;
use Symfony\Component\Debug\Debug;
use Throwable;
use function getenv;

final class HttpKernel implements KernelInterface
{
    /** @var RegistryInterface */
    private $container;

    /** @var ProviderAssemblerInterface */
    private $assembler;

    /** @var string */
    private $projectRoot;

    public function __construct(
        RegistryInterface $container,
        ProviderAssemblerInterface $assembler,
        string $projectRoot
    ) {
        $this->container = $container;
        $this->assembler = $assembler;
        $this->projectRoot = $projectRoot;
    }

    /**
     * @inheritdoc
     */
    public function run(): void
    {
        $this->loadEnvironment();

        try {
            $this->loadDependencies();

            $requestHandler = $this->getRequestHandler();
            $serverRequestBuilder = $this->getServerRequestBuilder();
            $responseEmitter = $this->getResponseEmitter();

            $response = $requestHandler->handle($serverRequestBuilder->buildFromGlobals());

            $responseEmitter->emit($response);
        } catch (Throwable $exception) {
            throw new KernelException('failed to handle request', $exception);
        }
    }

    /**
     * @throws ProviderException
     */
    private function loadDependencies(): void
    {
        $registry = new ProviderRegistry();
        $this->assembler->assemble($registry);
        foreach ($registry->getProviders() as $provider) {
            $provider->provide($this->container);
        }
    }

    private function loadEnvironment(): void
    {
        $dotEnv = Dotenv::create($this->projectRoot);
        $dotEnv->load();
        $dotEnv->required(['ENV']);

        $envValue = getenv('ENV');
        if ($envValue === false) {
            throw new KernelException('Invalid environment value in .env');
        }

        $env = new Environment($envValue);

        if ($env->equals(Environment::createDevelopment())) {
            Debug::enable();
        }

        $this->container->set(Environment::class, static function () use ($env) {
            return $env;
        });
    }

    private function getRequestHandler(): RequestHandlerInterface
    {
        return $this->container->get(RequestHandlerInterface::class);
    }

    private function getServerRequestBuilder(): ServerRequestBuilderInterface
    {
        return $this->container->get(ServerRequestBuilderInterface::class);
    }

    private function getResponseEmitter(): ResponseEmitterInterface
    {
        return $this->container->get(ResponseEmitterInterface::class);
    }
}
