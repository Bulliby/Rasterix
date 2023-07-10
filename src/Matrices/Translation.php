<?php

declare(strict_types=1);

namespace Waxer\Rasterix\Matrices;

use Waxer\Rasterix\Vector;

class Translation implements IMatrix
{
    public function __construct(
        private Vector $vector,
    ) {}

    /**
     * @return array<array<int>>
     */
    public function __invoke(): array
    {
        return [
            [1, 0, 0, 0],
            [0, 1, 0, 0],
            [0, 0, 1, 0],
            [$this->vector->getX(), $this->vector->getY(), $this->vector->getZ(), 1],
        ];
    }
}
