<?php declare(strict_types=1);

namespace Qlimix\Tests\Kernel\Http\Bootstrap\Environment;

use Dotenv\Dotenv;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Qlimix\Kernel\Http\Bootstrap\Environment\DotEnvLoader;
use Qlimix\Kernel\Bootstrap\Environment\Exception\LoaderException;
use RuntimeException;

final class DotEnvLoaderTest extends TestCase
{
    /** @var MockObject */
    private $dotEnv;

    /** @var DotEnvLoader */
    private $loader;

    protected function setUp(): void
    {
        $this->dotEnv = $this->createMock(Dotenv::class);

        $this->loader = new DotEnvLoader($this->dotEnv);
    }

    /**
     * @test
     */
    public function shouldLoad(): void
    {
        $this->dotEnv->expects($this->once())
            ->method('load');

        $this->dotEnv->expects($this->once())
            ->method('required');

        $this->loader->load();
    }

    /**
     * @test
     */
    public function shouldThrowOnMissingRequired(): void
    {
        $this->dotEnv->expects($this->once())
            ->method('load');

        $this->dotEnv->expects($this->once())
            ->method('required')
            ->willThrowException(new RuntimeException());

        $this->expectException(LoaderException::class);

        $this->loader->load();
    }
}
