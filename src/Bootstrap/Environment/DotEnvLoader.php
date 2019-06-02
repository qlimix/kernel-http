<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Bootstrap\Environment;

use Dotenv\Dotenv;
use Qlimix\DependencyContainer\RegistryInterface;
use Qlimix\Environment\Environment;
use Qlimix\Environment\Value\Loader;
use Qlimix\Kernel\Http\Bootstrap\Environment\Exception\LoaderException;
use Symfony\Component\Debug\Debug;
use Throwable;

final class DotEnvLoader implements LoaderInterface
{
    /** @var Dotenv */
    private $dotEnv;

    /** @var Loader */
    private $loader;

    /** @var RegistryInterface */
    private $container;

    public function __construct(Dotenv $dotEnv, Loader $loader, RegistryInterface $container)
    {
        $this->dotEnv = $dotEnv;
        $this->loader = $loader;
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function load(): void
    {
        try {
            $this->dotEnv->load();
            $this->dotEnv->required(['ENV']);

            $envValue = $this->loader->getString('ENV');

            $env = new Environment($envValue);

            if ($env->equals(Environment::createDevelopment())) {
                Debug::enable();
            }

            $this->container->set(Environment::class, static function () use ($env) {
                return $env;
            });
        } catch (Throwable $exception) {
            throw new LoaderException('Failed to load dotenv', 0, $exception);
        }
    }
}
