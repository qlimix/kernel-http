<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Bootstrap\Environment;

use Qlimix\Kernel\Http\Bootstrap\Environment\Exception\LoaderException;

interface LoaderInterface
{
    /**
     * @throws LoaderException
     */
    public function load(): void;
}
