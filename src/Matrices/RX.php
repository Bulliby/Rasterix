<?php

declare(strict_types=1);

namespace Waxer\Rasterix\Matrices;

class RX implements IMatrix
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
            [1, 0, 0, 0],
            [0, cos($this->angle), sin($this->angle), 0],
            [0, -sin($this->angle), cos($this->angle), 0],
            [0, 0, 0, 1],
        ];
    }
}
