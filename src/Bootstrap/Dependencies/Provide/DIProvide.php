<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Bootstrap\Dependencies\Provide;

use Qlimix\DependencyContainer\Registry\ProviderCollectionInterface;
use Qlimix\DependencyContainer\RegistryInterface;
use Qlimix\Kernel\Bootstrap\Dependencies\Provide\Exception\ProvideException;
use Qlimix\Kernel\Bootstrap\Dependencies\Provide\ProvideInterface;
use Throwable;

final class DIProvide implements ProvideInterface
{
    private RegistryInterface $registry;

    private ProviderCollectionInterface $collection;

    public function __construct(
        RegistryInterface $registry,
        ProviderCollectionInterface $collection
    ) {
        $this->registry = $registry;
        $this->collection = $collection;
    }

    /**
     * @inheritDoc
     */
    public function provide(): void
    {
        try {
            foreach ($this->collection->getProviders() as $provider) {
                $provider->provide($this->registry);
            }
        } catch (Throwable $exception) {
            throw new ProvideException('Failed to load DI dependencies', 0, $exception);
        }
    }
}
