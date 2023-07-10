<?php

declare(strict_types=1);

namespace Waxer\Rasterix\Matrices;

class Scale implements IMatrix
{
    public function __construct(
        private float $scale,
    ) {}

    /**
     * @return array<array<int>>
     */
    public function __invoke(): array
    {
        return [
            [$this->scale, 0, 0, 0],
            [0, $this->scale, 0, 0],
            [0, 0, $this->scale, 0],
            [0, 0, 0, 1],
        ];
    }
}
