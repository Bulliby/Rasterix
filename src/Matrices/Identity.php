<?php

declare(strict_types=1);

namespace Waxer\Rasterix\Matrices;

class Identity implements IMatrix
{
    /**
     * @return array<array<int>>
     */
    public function __invoke(): array
    {
        return [
            [1, 0, 0, 0],
            [0, 1, 0, 0],
            [0, 0, 1, 0],
            [0, 0, 0, 1],
        ];
    }
}
