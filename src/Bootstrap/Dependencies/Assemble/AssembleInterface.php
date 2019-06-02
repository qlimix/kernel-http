<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Bootstrap\Dependencies\Assemble;

use Qlimix\Kernel\Http\Bootstrap\Dependencies\Assemble\Exception\AssembleException;

interface AssembleInterface
{
    /**
     * @throws AssembleException
     */
    public function assemble(): void;
}
