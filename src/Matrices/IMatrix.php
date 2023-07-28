<?php

declare(strict_types=1);

namespace Waxer\Rasterix\Matrices;

interface IMatrix
{
    /**
     * @return array<array<int>>
     */
    public function __invoke(): array;
}
