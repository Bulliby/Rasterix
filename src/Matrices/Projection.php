<?php

declare(strict_types=1);

namespace Waxer\Rasterix\Matrices;

class Projection implements IMatrix
{
    public function __construct(
        private float $ratio,
        private float $near,
        private float $far,
        private float $fov,
    ) {}

    /**
     * @return array<array<int>>
     */
    public function __invoke(): array
    {
        $scale =  1 / tan(0.5 * deg2rad($this->fov)); 

        return [
            [$scale / $this->ratio, 0, 0, 0],
            [0, $scale, 0, 0],
            [0, 0, -1 * (-$this->near - $this->far) / ($this->near - $this->far), -1],
            [0, 0, (2 * $this->near * $this->far) / ($this->near - $this->far), 0],
        ];
    }
}
