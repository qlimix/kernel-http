<?php declare(strict_types=1);

namespace Qlimix\Kernel\Http\Http;

use Qlimix\Kernel\Http\Http\Exception\RequestProcessorException;

interface RequestProcessorInterface
{
    /**
     * @throws RequestProcessorException
     */
    public function process(): void;
}
