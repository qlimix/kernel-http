<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Bootstrap\Dependencies\Provide;

use Qlimix\Kernel\Http\Bootstrap\Dependencies\Provide\Exception\ProvideException;

interface ProvideInterface
{
    /**
     * @throws ProvideException
     */
    public function provide(): void;
}
