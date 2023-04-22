<?php

namespace Waxer\Rasterix;

class Matrix
{
    public const IDENTITY = "IDENTITY";
    public const SCALE = "SCALE";
    public const RX = "RX";
    public const RY = "RY";
    public const RZ = "RZ";
    public const TRANSLATION = "TRANSLATION";
    public const PROJECTION = "PROJECTION";
    public const MATRIX = "MATRIX";

    public const SIZE = 4;

    private float $scale;
    // Radian
    private float $angle;
    private Vector $vtc;

    // Projection
    private float $ratio;
    private float $near;
    private float $far;

    private float $fov;
    
    /**
     * @var array<array<int>> $matrix
     */
    public array $matrix = [];

    public static bool $verbose = false;

    /**
     * @param array<string|Vector|float> $args
     */
    public function __construct(array $args)
    {
        if (false === array_key_exists('preset', $args)) {
            throw new InvalidArgumentsException();
        }
        switch ($args['preset']) {
            case self::IDENTITY:
                $this->createIdentityMatrix();
                break;
            case self::SCALE:
                if (false === array_key_exists('scale', $args)) {
                    throw new InvalidArgumentsException();
                }
                $this->scale = $args['scale'];
                $this->createScaleMatrix();
                break;
            case self::RX:
                if (false === array_key_exists('angle', $args)) {
                    throw new InvalidArgumentsException();
                }
                $this->angle = $args['angle'];
                $this->createRxMatrix();
                break;
            case self::RY:
                if (false === array_key_exists('angle', $args)) {
                    throw new InvalidArgumentsException();
                }
                $this->angle = $args['angle'];
                $this->createRyMatrix();
                break;
            case self::RZ:
                if (false === array_key_exists('angle', $args)) {
                    throw new InvalidArgumentsException();
                }
                $this->angle = $args['angle'];
                $this->createRzMatrix();
                break;
            case self::TRANSLATION:
                if (false === array_key_exists('vtc', $args)) {
                    throw new InvalidArgumentsException();
                }
                $this->vtc = $args['vtc'];
                $this->createTranslationMatrix();
                break;
            case self::PROJECTION:
                if (false === array_key_exists('fov', $args)
                    && array_key_exists('ratio', $args)
                    && array_key_exists('near', $args)
                    && array_key_exists('far', $args)
                ) {
                    throw new InvalidArgumentsException();
                }
                $this->fov = $args['fov'];
                $this->ratio = $args['ratio'];
                $this->near = $args['near'];
                $this->far = $args['far'];
                $this->createProjectionMatrix();
                break;
            default:
                throw new InvalidArgumentsException();
        }
    }

    /**
     * @return array<array<int>>
     */
    private function createIdentityMatrix(): array
    {
        return $this->matrix = [
            [1, 0, 0, 0],
            [0, 1, 0, 0],
            [0, 0, 1, 0],
            [0, 0, 0, 1],
        ];
    }

    /**
     * @return array<array<int>>
     */
    private function createTranslationMatrix(): array
    {
        return $this->matrix = [
            [1, 0, 0, 0],
            [0, 1, 0, 0],
            [0, 0, 1, 0],
            [$this->vtc->getX(), $this->vtc->getY(), $this->vtc->getZ(), 1],
        ];
    }

    /**
     * @return array<array<int>>
     */
    private function createScaleMatrix(): array
    {
        return $this->matrix = [
            [$this->scale, 0, 0, 0],
            [0, $this->scale, 0, 0],
            [0, 0, $this->scale, 0],
            [0, 0, 0, 1],
        ];
    }


    /**
     * @return array<array<int>>
     */
    private function createRxMatrix(): array
    {
        return $this->matrix = [
            [1, 0, 0, 0],
            [0, cos($this->angle), sin($this->angle), 0],
            [0, -sin($this->angle), cos($this->angle), 0],
            [0, 0, 0, 1],
        ];
    }

    /**
     * @return array<array<int>>
     */
    private function createRyMatrix(): array
    {
        return $this->matrix = [
            [cos($this->angle), 0, -sin($this->angle), 0],
            [0, 1, 0, 0],
            [sin($this->angle), 0, cos($this->angle), 0],
            [0, 0, 0, 1],
        ];
    }

    /**
     * @return array<array<int>>
     */
    private function createRzMatrix(): array
    {
        return $this->matrix = [
            [cos($this->angle), sin($this->angle), 0, 0],
            [-sin($this->angle), cos($this->angle), 0, 0],
            [0, 0, 1, 0],
            [0, 0, 0, 1],
        ];
    }

    /**
     * @return array<array<int>>
     */
    private function createProjectionMatrix(): array
    {
        $scale =  1 / tan(0.5 * deg2rad($this->fov)); 

        return $this->matrix = [
            [$scale / $this->ratio, 0, 0, 0],
            [0, $scale, 0, 0],
            [0, 0, -1 * (-$this->near - $this->far) / ($this->near - $this->far), -1],
            [0, 0, (2 * $this->near * $this->far) / ($this->near - $this->far), 0],
        ];
    }

    private function outputMatrix(): string
    {
        $output = "M | vtcX | vtcY | vtcZ | vtxO\n-----------------------------\n";

        $lineX = "";
        $lineY = "";
        $lineZ = "";
        $lineO = "";

        for ($j = 0; $j != self::SIZE; $j++) {
            $lineX .= sprintf("%.2f | ", $this->matrix[$j][0]);
            $lineY .= sprintf("%.2f | ", $this->matrix[$j][1]);
            $lineZ .= sprintf("%.2f | ", $this->matrix[$j][2]);
            $lineO .= sprintf("%.2f | ", $this->matrix[$j][3]);
        }

        return sprintf("%sx | %s\ny | %s\nz | %s\nw | %s\n", $output, $lineX, $lineY, $lineZ, $lineO);
    }

    public function multiplication(Vertex $vertex): Vertex
    {
        $vertex = $vertex->toArray();

        $ret = [];

        for ($i = 0; $i != self::SIZE; $i++)
        {
            for($j = 0; $j != self::SIZE; $j++)
            {
                $ret[$i] += $vertex[$j] * $this->matrix[$j][$i];
            }
        }

        return Vertex::toVertex($ret);
    }

    public function mult(Matrix $rhs): self
    {
        $ret = new Matrix(['preset' => self::IDENTITY]);

        for ($i = 0; $i != self::SIZE; $i++)
        {
            for($j = 0; $j != self::SIZE; $j++)
            {
                $ret->matrix[$i][$j] =  $rhs->matrix[$i][0] * $this->matrix[0][$j] + 
                    $rhs->matrix[$i][1] * $this->matrix[1][$j] +
                    $rhs->matrix[$i][2] * $this->matrix[2][$j] +
                    $rhs->matrix[$i][3] * $this->matrix[3][$j];
            }
        }

        return $ret; 
    }

    /**
     * @return void
     */
    public function __destruct()
    {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->outputMatrix();
    }

    public function getAngle(): float
    {
        return $this->angle;
    }

    public function getVtc(): Vector
    {
        return $this->vtc;
    }

    public function getRatio(): float
    {
        return $this->ratio;
    }

    public function getNear(): float
    {
        return $this->near;
    }

    public function getFar(): float
    {
        return $this->far;
    }

    /**
     * @return array<array<int>>
     */
    public function getMatrix(): array
    {
        return $this->matrix;
    }

    /**
     * @param array<array<int>> $rhs
     */
    public function setMatrix(array $rhs): self
    {
        $this->matrix = $rhs;

        return $this;
    }
}
