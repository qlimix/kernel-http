<?php declare(strict_types=1);

namespace Qlimix\Tests\Kernel\Http;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Qlimix\Kernel\Bootstrap\BootstrapInterface;
use Qlimix\Kernel\Bootstrap\Exception\BootstrapException;
use Qlimix\Kernel\Exception\KernelException;
use Qlimix\Kernel\Http\HttpKernel;
use Qlimix\Kernel\Http\Processor\Exception\RequestProcessorException;
use Qlimix\Kernel\Http\Processor\RequestProcessorInterface;

final class HttpKernelTest extends TestCase
{
    /** @var MockObject */
    private $bootstrap;

    /** @var MockObject */
    private $processor;

    /** @var HttpKernel */
    private $kernel;

    protected function setUp(): void
    {
        $this->bootstrap = $this->createMock(BootstrapInterface::class);
        $this->processor = $this->createMock(RequestProcessorInterface::class);

        $this->kernel = new HttpKernel($this->bootstrap, $this->processor);
    }

    /**
     * @test
     */
    public function shouldRun(): void
    {
        $this->bootstrap->expects($this->once())
            ->method('bootstrap');

        $this->processor->expects($this->once())
            ->method('process');

        $this->kernel->run();
    }

    /**
     * @test
     */
    public function shouldThrowOnBootstrapFailure(): void
    {
        $this->bootstrap->expects($this->once())
            ->method('bootstrap')
            ->willThrowException(new BootstrapException());

        $this->processor->expects($this->never())
            ->method('process');

        $this->expectException(KernelException::class);

        $this->kernel->run();
    }

    /**
     * @test
     */
    public function shouldThrowOnProcessFailure(): void
    {
        $this->bootstrap->expects($this->once())
            ->method('bootstrap');

        $this->processor->expects($this->once())
            ->method('process')
            ->willThrowException(new RequestProcessorException());

        $this->expectException(KernelException::class);

        $this->kernel->run();
    }
}
