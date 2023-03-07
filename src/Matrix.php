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

   public const SIZE = 4;

    private float $_scale;
    // Radian
    private float $_angle;
    private Vector $_vtc;

    // Projection
    private float $_ratio;
    private float $_near;
    private float $_far;

    // Field of view
    private float $_fov;

    /**
     * @var array<Vector>
     */
    private array $_matrix = [];

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
                $this->_scale = $args['scale'];
                $this->createScaleMatrix();
                break;
            case self::RX:
                if (false === array_key_exists('angle', $args)) {
                    throw new InvalidArgumentsException();
                }
                $this->_angle = $args['angle'];
                $this->createRxMatrix();
                break;
            case self::RY:
                if (false === array_key_exists('angle', $args)) {
                    throw new InvalidArgumentsException();
                }
                $this->_angle = $args['angle'];
                $this->createRyMatrix();
                break;
            case self::RZ:
                if (false === array_key_exists('angle', $args)) {
                    throw new InvalidArgumentsException();
                }
                $this->_angle = $args['angle'];
                $this->createRzMatrix();
                break;
            case self::TRANSLATION:
                if (false === array_key_exists('vtc', $args)) {
                    throw new InvalidArgumentsException();
                }
                $this->_vtc = $args['vtc'];
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
                $this->_fov = $args['fov'];
                $this->_ratio = $args['ratio'];
                $this->_near = $args['near'];
                $this->_far = $args['far'];
                $this->createProjectionMatrix();
                break;
            default:
                throw new InvalidArgumentsException();
        }
    }

    private function createIdentityMatrix(): void
    {
        $vtx = new Vertex(['x' => 1, 'y' => 0, 'z' => 0, 'w' => 1]);
        $vty = new Vertex(['x' => 0, 'y' => 1, 'z' => 0, 'w' => 1]);
        $vtz = new Vertex(['x' => 0, 'y' => 0, 'z' => 1, 'w' => 1]);
        $vto = new Vertex(['x' => 0, 'y' => 0, 'z' => 0, 'w' => 2]);

        $vtcX = new Vector(['dest' => $vtx]);
        $vtcY = new Vector(['dest' => $vty]);
        $vtcZ = new Vector(['dest' => $vtz]);
        $vtcO = new Vector(['dest' => $vto]);


        $this->_matrix[0] = $vtcX;
        $this->_matrix[1] = $vtcY;
        $this->_matrix[2] = $vtcZ;
        $this->_matrix[3] = $vtcO;
    }

    private function createScaleMatrix(): void
    {
        $vtx = new Vertex(['x' => $this->_scale, 'y' => 0, 'z' => 0, 'w' => 1]);
        $vty = new Vertex(['x' => 0, 'y' => $this->_scale, 'z' => 0, 'w' => 1]);
        $vtz = new Vertex(['x' => 0, 'y' => 0, 'z' => $this->_scale, 'w' => 1]);
        $vto = new Vertex(['x' => 0, 'y' => 0, 'z' => 0, 'w' => 2]);

        $vtcX = new Vector(['dest' => $vtx]);
        $vtcY = new Vector(['dest' => $vty]);
        $vtcZ = new Vector(['dest' => $vtz]);
        $vtcO = new Vector(['dest' => $vto]);


        $this->_matrix[0] = $vtcX;
        $this->_matrix[1] = $vtcY;
        $this->_matrix[2] = $vtcZ;
        $this->_matrix[3] = $vtcO;
    }


    private function createRxMatrix(): void
    {
        $vtx = new Vertex(['x' => 1, 'y' => 0, 'z' => 0, 'w' => 1]);
        $vty = new Vertex(['x' => 0, 'y' => cos($this->_angle), 'z' => -sin($this->_angle), 'w' => 1]);
        $vtz = new Vertex(['x' => 0, 'y' => sin($this->_angle), 'z' => cos($this->_angle), 'w' => 1]);
        $vto = new Vertex(['x' => 0, 'y' => 0, 'z' => 0, 'w' => 2]);

        $vtcX = new Vector(['dest' => $vtx]);
        $vtcY = new Vector(['dest' => $vty]);
        $vtcZ = new Vector(['dest' => $vtz]);
        $vtcO = new Vector(['dest' => $vto]);


        $this->_matrix[0] = $vtcX;
        $this->_matrix[1] = $vtcY;
        $this->_matrix[2] = $vtcZ;
        $this->_matrix[3] = $vtcO;
    }

    private function createRyMatrix(): void
    {
        $vtx = new Vertex(['x' => cos($this->_angle), 'y' => 0, 'z' => sin($this->_angle), 'w' => 1]);
        $vty = new Vertex(['x' => 0, 'y' => 1, 'z' => 0, 'w' => 1]);
        $vtz = new Vertex(['x' => -sin($this->_angle), 'y' => 0, 'z' => cos($this->_angle), 'w' => 1]);
        $vto = new Vertex(['x' => 0, 'y' => 0, 'z' => 0, 'w' => 2]);

        $vtcX = new Vector(['dest' => $vtx]);
        $vtcY = new Vector(['dest' => $vty]);
        $vtcZ = new Vector(['dest' => $vtz]);
        $vtcO = new Vector(['dest' => $vto]);


        $this->_matrix[0] = $vtcX;
        $this->_matrix[1] = $vtcY;
        $this->_matrix[2] = $vtcZ;
        $this->_matrix[3] = $vtcO;
    }

    private function createRzMatrix(): void
    {
        $vtx = new Vertex(['x' => cos($this->_angle), 'y' => -sin($this->_angle), 'z' => 0, 'w' => 1]);
        $vty = new Vertex(['x' => sin($this->_angle), 'y' => cos($this->_angle), 'z' => 0, 'w' => 1]);
        $vtz = new Vertex(['x' => 0, 'y' => 0, 'z' => 1, 'w' => 1]);
        $vto = new Vertex(['x' => 0, 'y' => 0, 'z' => 0, 'w' => 2]);

        $vtcX = new Vector(['dest' => $vtx]);
        $vtcY = new Vector(['dest' => $vty]);
        $vtcZ = new Vector(['dest' => $vtz]);
        $vtcO = new Vector(['dest' => $vto]);


        $this->_matrix[0] = $vtcX;
        $this->_matrix[1] = $vtcY;
        $this->_matrix[2] = $vtcZ;
        $this->_matrix[3] = $vtcO;
    }

    private function createProjectionMatrix(): void
    {
        $scale =  1 / tan(0.5 * deg2rad($this->_fov)); 
        $vtx = new Vertex(['x' => $scale / $this->_ratio, 'y' => 0, 'z' => 0, 'w' => 1]);
        $vty = new Vertex(['x' => 0, 'y' => $scale, 'z' => 0, 'w' => 1]);
        $vtz = new Vertex(['x' => 0, 'y' => 0, 'z' => - 1 * (- $this->_near - $this->_far) / ($this->_near - $this->_far), 'w' => 0]);
        $vto = new Vertex(['x' => 0, 'y' => 0, 'z' => (2 * $this->_near * $this->_far) /($this->_near - $this->_far), 'w' => 1]);

        $vtcX = new Vector(['dest' => $vtx]);
        $vtcY = new Vector(['dest' => $vty]);
        $vtcZ = new Vector(['dest' => $vtz]);
        $vtcO = new Vector(['dest' => $vto]);


        $this->_matrix[0] = $vtcX;
        $this->_matrix[1] = $vtcY;
        $this->_matrix[2] = $vtcZ;
        $this->_matrix[3] = $vtcO;
    }

    private function outputMatrix(): string
    {
        $output = "M | vtcX | vtcY | vtcZ | vtxO\n-----------------------------\n";

        $lineX = "";
        $lineY = "";
        $lineZ = "";
        $lineO = "";

        for ($x = 0; $x != self::SIZE; $x++) {
            $lineX .= sprintf("%.2f | ", $this->_matrix[$x]->getX());
            $lineY .= sprintf("%.2f | ", $this->_matrix[$x]->getY());
            $lineZ .= sprintf("%.2f | ", $this->_matrix[$x]->getZ());
            $lineO .= sprintf("%.2f | ", $this->_matrix[$x]->getW());
        }

        return sprintf("%sx | %s\ny | %s\nz | %s\nw | %s\n", $output, $lineX, $lineY, $lineZ, $lineO);
    }

    private function createTranslationMatrix(): void
    {
        $vtx = new Vertex(['x' => 1, 'y' => 0, 'z' => 0, 'w' => 1]);
        $vty = new Vertex(['x' => 0, 'y' => 1, 'z' => 0, 'w' => 1]);
        $vtz = new Vertex(['x' => 0, 'y' => 0, 'z' => 1, 'w' => 1]);
        $vto = new Vertex(['x' => $this->_vtc->getX(), 'y' => $this->_vtc->getY(), 'z' => $this->_vtc->getZ(), 'w' => 2]);

        $vtcX = new Vector(['dest' => $vtx]);
        $vtcY = new Vector(['dest' => $vty]);
        $vtcZ = new Vector(['dest' => $vtz]);
        $vtcO = new Vector(['dest' => $vto]);


        $this->_matrix[0] = $vtcX;
        $this->_matrix[1] = $vtcY;
        $this->_matrix[2] = $vtcZ;
        $this->_matrix[3] = $vtcO;
    }

    public function applyTransaltion(Vertex $point): Vertex
    {
        return new Vertex([
            'x' => $point->getX() + $this->_matrix[3]->getX(),
            'y' => $point->getY() + $this->_matrix[3]->getY(),
            'z' => $point->getZ() + $this->_matrix[3]->getZ(),
            'w' => 1,
        ]);
    }

    public function multiplication (Vertex $point): Vertex
    {
        return new Vertex([
            'x' => $point->getX() * $this->_matrix[0]->getX() 
                + $point->getY() * $this->_matrix[0]->getY() 
                + $point->getZ() * $this->_matrix[0]->getZ() 
                + $this->_matrix[0]->getW(),
            'y' => $point->getX() * $this->_matrix[1]->getX() 
                + $point->getY() * $this->_matrix[1]->getY() 
                + $point->getZ() * $this->_matrix[1]->getZ() 
                + $this->_matrix[1]->getW(),
            'z' => $point->getX() * $this->_matrix[2]->getX() 
                + $point->getY() * $this->_matrix[2]->getY() 
                + $point->getZ() * $this->_matrix[2]->getZ() 
                + $this->_matrix[2]->getW(),
            'w' => $point->getX() * $this->_matrix[3]->getX() 
                + $point->getY() * $this->_matrix[3]->getY() 
                + $point->getZ() * $this->_matrix[3]->getZ() 
                + $this->_matrix[3]->getW()
        ]);
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

    public function getScale(): float
    {
        return $this->_scale;
    }

    public function getAngle(): float
    {
        return $this->_angle;
    }

    public function getVtc(): Vector
    {
        return $this->_vtc;
    }

    public function getRatio(): float
    {
        return $this->_ratio;
    }

    public function getNear(): float
    {
        return $this->_near;
    }

    public function getFar(): float
    {
        return $this->_far;
    }
}
