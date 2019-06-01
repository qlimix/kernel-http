<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http;

use Qlimix\Kernel\Bootstrap\BootstrapInterface;
use Qlimix\Kernel\Exception\KernelException;
use Qlimix\Kernel\Http\Http\RequestProcessorInterface;
use Qlimix\Kernel\KernelInterface;
use Throwable;

final class HttpKernel implements KernelInterface
{
    /** @var BootstrapInterface */
    private $bootstrap;

    /** @var RequestProcessorInterface */
    private $processor;

    public function __construct(BootstrapInterface $bootstrap, RequestProcessorInterface $processor)
    {
        $this->bootstrap = $bootstrap;
        $this->processor = $processor;
    }

    /**
     * @inheritdoc
     */
    public function run(): void
    {
        try {
            $this->bootstrap->bootstrap();
            $this->processor->process();
        } catch (Throwable $exception) {
            throw new KernelException('failed to handle request', $exception);
        }
    }
}
