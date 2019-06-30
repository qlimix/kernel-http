<?php declare(strict_types=1);

namespace Qlimix\Tests\Kernel\Http\Bootstrap\Dependencies;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Qlimix\Kernel\Http\Bootstrap\Dependencies\Assemble\AssembleInterface;
use Qlimix\Kernel\Http\Bootstrap\Dependencies\Assemble\Exception\AssembleException;
use Qlimix\Kernel\Http\Bootstrap\Dependencies\DILoader;
use Qlimix\Kernel\Http\Bootstrap\Dependencies\Exception\LoaderException;
use Qlimix\Kernel\Http\Bootstrap\Dependencies\Provide\Exception\ProvideException;
use Qlimix\Kernel\Http\Bootstrap\Dependencies\Provide\ProvideInterface;

final class DILoaderTest extends TestCase
{
    /** @var MockObject */
    private $assemble;

    /** @var MockObject */
    private $provide;

    /** @var DILoader */
    private $loader;

    protected function setUp(): void
    {
        $this->assemble = $this->createMock(AssembleInterface::class);
        $this->provide = $this->createMock(ProvideInterface::class);

        $this->loader = new DILoader($this->assemble, $this->provide);
    }

    /**
     * @test
     */
    public function shouldLoad(): void
    {
        $this->assemble->expects($this->once())
            ->method('assemble');

        $this->provide->expects($this->once())
            ->method('provide');

        $this->loader->load();
    }

    /**
     * @test
     */
    public function shouldThrowOnAssembleFailure(): void
    {
        $this->assemble->expects($this->once())
            ->method('assemble')
            ->willThrowException(new AssembleException());

        $this->provide->expects($this->never())
            ->method('provide');

        $this->expectException(LoaderException::class);

        $this->loader->load();
    }

    /**
     * @test
     */
    public function shouldThrowOnProvideFailure(): void
    {
        $this->assemble->expects($this->once())
            ->method('assemble');

        $this->provide->expects($this->once())
            ->method('provide')
            ->willThrowException(new ProvideException());

        $this->expectException(LoaderException::class);

        $this->loader->load();
    }
}
