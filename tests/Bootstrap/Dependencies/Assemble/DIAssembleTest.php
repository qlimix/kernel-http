<?php declare(strict_types=1);

namespace Qlimix\Tests\Kernel\Http\Bootstrap\Dependencies\Assemble;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Qlimix\DependencyContainer\Registry\ProviderAssemblerInterface;
use Qlimix\DependencyContainer\Registry\ProviderRegistryInterface;
use Qlimix\Kernel\Http\Bootstrap\Dependencies\Assemble\DIAssemble;

final class DIAssembleTest extends TestCase
{
    /** @var MockObject */
    private $assembler;

    /** @var MockObject */
    private $registry;

    /** @var DIAssemble */
    private $assemble;

    protected function setUp(): void
    {
        $this->assembler = $this->createMock(ProviderAssemblerInterface::class);
        $this->registry = $this->createMock(ProviderRegistryInterface::class);

        $this->assemble = new DIAssemble($this->assembler, $this->registry);
    }

    /**
     * @test
     */
    public function shouldAssemble(): void
    {
        $this->assembler->expects($this->once())
            ->method('assemble');

        $this->assemble->assemble();
    }
}
