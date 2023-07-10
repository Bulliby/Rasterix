<?php

declare(strict_types=1);

namespace Waxer\Rasterix\Matrices;

use Waxer\Rasterix\Vertex;
use Waxer\Rasterix\Vector;

class View implements IMatrix
{
    public function __construct(
        private Vertex $from,
        private Vertex $to,
    ) {}

    /**
     * @return array<array<int>>
     */
    public function __invoke(): array
    {
        $tmp = new Vertex(['x' => 0, 'y' => 1, 'z' => 0]);
        $VectorTmp = new Vector(['dest' => $tmp]);

        $VectorFrom = new Vector(['dest' => $this->from]);
        $VectorTo = new Vector(['dest' => $this->to]);

        $VectorForward = $VectorFrom->sub($VectorTo);
        $VectorForward = $VectorForward->normalize();
        $VectorRight = $VectorTmp->crossProduct($VectorForward);
        $VectorRight = $VectorRight->normalize();
        $VectorUp = $VectorForward->crossProduct($VectorRight);

        return [
            [$VectorRight->getX(), $VectorRight->getY(), $VectorRight->getZ(), 0],
            [$VectorUp->getX(), $VectorUp->getY(), $VectorUp->getZ(), 0],
            [$VectorForward->getX(), $VectorForward->getY(), $VectorForward->getZ(), 0],
            [$this->from->getX(), $this->from->getY(), $this->from->getZ(), 1],
        ];
    }
}
