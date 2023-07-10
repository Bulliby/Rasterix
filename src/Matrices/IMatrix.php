<?php

declare(strict_types=1);

namespace Waxer\Rasterix\Matrices;

interface IMatrix
{
    public function __invoke(): array;
}
