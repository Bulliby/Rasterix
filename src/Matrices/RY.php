<?php

declare(strict_types=1);

namespace Waxer\Rasterix\Matrices;

class RY implements IMatrix
{
    public function __construct(
        private float $angle,
    ) {}

    /**
     * @return array<array<int>>
     */
    public function __invoke(): array
    {
        return [
            [cos($this->angle), 0, -sin($this->angle), 0],
            [0, 1, 0, 0],
            [sin($this->angle), 0, cos($this->angle), 0],
            [0, 0, 0, 1],
        ];
    }
}
