<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Bootstrap\Environment;

use Dotenv\Dotenv;
use Qlimix\Kernel\Http\Bootstrap\Environment\Exception\LoaderException;
use Throwable;

final class DotEnvLoader implements LoaderInterface
{
    /** @var Dotenv */
    private $dotEnv;

    public function __construct(Dotenv $dotEnv)
    {
        $this->dotEnv = $dotEnv;
    }

    /**
     * @inheritDoc
     */
    public function load(): void
    {
        try {
            $this->dotEnv->load();
            $this->dotEnv->required(['ENV']);
        } catch (Throwable $exception) {
            throw new LoaderException('Failed to load dotenv', 0, $exception);
        }
    }
}
