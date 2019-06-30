<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http;

use Qlimix\Kernel\Bootstrap\BootstrapInterface;
use Qlimix\Kernel\Bootstrap\Exception\BootstrapException;
use Qlimix\Kernel\Http\Bootstrap\Dependencies\LoaderInterface as DepLoaderInterface;
use Qlimix\Kernel\Http\Bootstrap\Environment\LoaderInterface as EnvLoaderInterface;
use Throwable;

final class HttpBootstrap implements BootstrapInterface
{
    /** @var EnvLoaderInterface */
    private $envLoader;

    /** @var DepLoaderInterface */
    private $depLoader;

    public function __construct(EnvLoaderInterface $envLoader, DepLoaderInterface $depLoader)
    {
        $this->envLoader = $envLoader;
        $this->depLoader = $depLoader;
    }

    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        try {
            $this->envLoader->load();
            $this->depLoader->load();
        } catch (Throwable $exception) {
            throw new BootstrapException('Failed to bootstrap', 0, $exception);
        }
    }
}
