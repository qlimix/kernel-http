<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Processor;

use Qlimix\Kernel\Http\Processor\Exception\RequestProcessorException;

interface RequestProcessorInterface
{
    /**
     * @throws RequestProcessorException
     */
    public function process(): void;
}
