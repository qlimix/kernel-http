<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Bootstrap\Dependencies\Provide;

use Qlimix\DependencyContainer\Registry\ProviderCollectionInterface;
use Qlimix\DependencyContainer\RegistryInterface;
use Qlimix\Kernel\Http\Bootstrap\Dependencies\Provide\Exception\ProvideException;
use Throwable;

final class DIProvide implements ProvideInterface
{
    /** @var RegistryInterface */
    private $container;

    /** @var ProviderCollectionInterface */
    private $collection;

    public function __construct(
        RegistryInterface $container,
        ProviderCollectionInterface $collection
    ) {
        $this->container = $container;
        $this->collection = $collection;
    }

    /**
     * @inheritDoc
     */
    public function provide(): void
    {
        try {
            foreach ($this->collection->getProviders() as $provider) {
                $provider->provide($this->container);
            }
        } catch (Throwable $exception) {
            throw new ProvideException('Failed to load DI dependencies', 0, $exception);
        }
    }
}
