<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Bootstrap\Dependencies\Assemble;

use Qlimix\DependencyContainer\Registry\ProviderAssemblerInterface;
use Qlimix\DependencyContainer\Registry\ProviderRegistryInterface;
use Qlimix\Kernel\Bootstrap\Dependencies\Assemble\AssembleInterface;

final class DIAssemble implements AssembleInterface
{
    private ProviderAssemblerInterface $assembler;

    private ProviderRegistryInterface $registry;

    public function __construct(ProviderAssemblerInterface $assembler, ProviderRegistryInterface $registry)
    {
        $this->assembler = $assembler;
        $this->registry = $registry;
    }

    /**
     * @inheritDoc
     */
    public function assemble(): void
    {
        $this->assembler->assemble($this->registry);
    }
}
