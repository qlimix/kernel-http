<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Bootstrap\Dependencies\Assemble;

use Qlimix\DependencyContainer\Registry\ProviderAssemblerInterface;
use Qlimix\DependencyContainer\Registry\ProviderRegistryInterface;

final class DIAssemble implements AssembleInterface
{
    /** @var ProviderAssemblerInterface */
    private $assembler;

    /** @var ProviderRegistryInterface */
    private $registry;

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
