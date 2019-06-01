<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Bootstrap;

use Dotenv\Dotenv;
use Qlimix\DependencyContainer\Exception\ProviderException;
use Qlimix\DependencyContainer\Registry\ProviderAssemblerInterface;
use Qlimix\DependencyContainer\Registry\ProviderRegistry;
use Qlimix\DependencyContainer\RegistryInterface;
use Qlimix\Environment\Environment;
use Qlimix\Environment\Value\Exception\LoaderException;
use Qlimix\Environment\Value\Loader;
use Qlimix\Kernel\Bootstrap\BootstrapInterface;
use Qlimix\Kernel\Bootstrap\Exception\BootstrapException;
use Symfony\Component\Debug\Debug;
use Throwable;

final class HttpBootstrap implements BootstrapInterface
{
    /** @var RegistryInterface */
    private $container;

    /** @var ProviderAssemblerInterface */
    private $assembler;

    /** @var Loader */
    private $loader;

    /** @var string */
    private $projectRoot;

    public function __construct(
        RegistryInterface $container,
        ProviderAssemblerInterface $assembler,
        Loader $loader,
        string $projectRoot
    ) {
        $this->container = $container;
        $this->assembler = $assembler;
        $this->loader = $loader;
        $this->projectRoot = $projectRoot;
    }

    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        try {
            $this->container->setValue('kernel.project.root', $this->projectRoot);

            $this->environment();
            $this->dependencies();
        } catch (Throwable $exception) {
            throw new BootstrapException('Failed to bootstrap', 0, $exception);
        }
    }

    /**
     * @throws ProviderException
     */
    private function dependencies(): void
    {
        $registry = new ProviderRegistry();
        $this->assembler->assemble($registry);
        foreach ($registry->getProviders() as $provider) {
            $provider->provide($this->container);
        }
    }

    /**
     * @throws LoaderException
     */
    private function environment(): void
    {
        $dotEnv = Dotenv::create($this->projectRoot);
        $dotEnv->load();
        $dotEnv->required(['ENV']);

        $envValue = $this->loader->getString('ENV');

        $env = new Environment($envValue);

        if ($env->equals(Environment::createDevelopment())) {
            Debug::enable();
        }

        $this->container->set(Environment::class, static function () use ($env) {
            return $env;
        });
    }
}
