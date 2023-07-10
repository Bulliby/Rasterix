<?php

declare(strict_types=1);

namespace Waxer\Rasterix\Matrices;

use Waxer\Rasterix\Enums\MatrixType;
use Waxer\Rasterix\Matrices\Identity;
use Waxer\Rasterix\Matrices\Scale;
use Waxer\Rasterix\Matrices\Translation;
use Waxer\Rasterix\Matrices\RX;
use Waxer\Rasterix\Matrices\RZ;
use Waxer\Rasterix\Matrices\RY;
use Waxer\Rasterix\Matrices\Projection;
use Waxer\Rasterix\Matrices\Custom;
use Waxer\Rasterix\Matrices\View;
use Waxer\Rasterix\Vertex;

class Matrix
{
    const SIZE = 4;

    private array $matrix;

    public function __construct(MatrixType $preset, ...$params)
    {
        $this->matrix = match($preset) {
            MatrixType::Identity => (new Identity)(),
            MatrixType::Scale => (new Scale($params[0]))(),
            MatrixType::Translation => (new Translation($params[0]))(),
            MatrixType::RX => (new RX($params[0]))(),
            MatrixType::RY => (new RY($params[0]))(),
            MatrixType::RZ => (new RZ($params[0]))(),
            MatrixType::Inverse => (new Inverse($params[0]))(),
            MatrixType::Projection => (new Projection($params[0], $params[1], $params[2], $params[3]))(),
            MatrixType::Custom => (new Custom($params[0]))(),
            MatrixType::View => (new View($params[0], $params[1]))(),
        };
    }

    public function getMatrix(): array
    {
        return $this->matrix;
    }

    public function transformVertex(Vertex $vertex): Vertex
    {
        $color = $vertex->getColor();
        $vertex = $vertex->toArray();

        $ret = [];

        for ($i = 0; $i != self::SIZE; $i++)
        {
            for($j = 0; $j != self::SIZE; $j++)
            {
                $ret[$i] ?? array_push($ret, 0);
                $ret[$i] += $vertex[$j] * $this->matrix[$j][$i];
            }
        }

        return Vertex::toVertex($ret, $color);
    }

    public function multMatrix(Matrix $matrix): self
    {
        $ret = new Matrix(MatrixType::Identity);

        for ($i = 0; $i != self::SIZE; $i++)
        {
            for($j = 0; $j != self::SIZE; $j++)
            {
                $ret->matrix[$i][$j] =  $matrix->matrix[$i][0] * $this->matrix[0][$j] + 
                    $matrix->matrix[$i][1] * $this->matrix[1][$j] +
                    $matrix->matrix[$i][2] * $this->matrix[2][$j] +
                    $matrix->matrix[$i][3] * $this->matrix[3][$j];
            }
        }

        return $ret; 
    }

}
