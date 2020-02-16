<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Bootstrap\Dependencies;

use Qlimix\Kernel\Bootstrap\Dependencies\Assemble\AssembleInterface;
use Qlimix\Kernel\Bootstrap\Dependencies\Exception\LoaderException;
use Qlimix\Kernel\Bootstrap\Dependencies\LoaderInterface;
use Qlimix\Kernel\Bootstrap\Dependencies\Provide\ProvideInterface;
use Throwable;

final class DILoader implements LoaderInterface
{
    private AssembleInterface $assemble;

    private ProvideInterface $provide;

    public function __construct(AssembleInterface $assemble, ProvideInterface $provide)
    {
        $this->assemble = $assemble;
        $this->provide = $provide;
    }

    /**
     * @inheritDoc
     */
    public function load(): void
    {
        try {
            $this->assemble->assemble();
            $this->provide->provide();
        } catch (Throwable $exception) {
            throw new LoaderException('Failed to load DI dependencies', 0, $exception);
        }
    }
}
