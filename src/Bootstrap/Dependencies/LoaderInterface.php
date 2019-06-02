<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Bootstrap\Dependencies;

use Qlimix\Kernel\Http\Bootstrap\Dependencies\Exception\LoaderException;

interface LoaderInterface
{
    /**
     * @throws LoaderException
     */
    public function load(): void;
}
