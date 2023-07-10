<?php

declare(strict_types=1);

namespace Waxer\Rasterix\Matrices;

use Waxer\Rasterix\Vertex;

class Custom implements IMatrix
{
    public function __construct(
        private Vertex $point,
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
            [$this->point->getX(), $this->point->getY(), $this->point->getZ(), 1],
        ];
    }
}
