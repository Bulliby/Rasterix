<?php

namespace Waxer\Rasterix;

class Vector
{
    private float $x;
    private float $y;
    private float $z;
    private float $w;
    private Vertex $dest;
    private Vertex $orig;

    public static bool $verbose = false;

    /**
     * @param array<Vertex> $args
     */
    public function __construct(array $args)
    {
        if (false === array_key_exists('dest', $args)) {
            throw new InvalidArgumentsException();
        }

        $this->dest = $args['dest'];
        $this->orig = $args['orig'] ?? new Vertex([]);

        $this->x = $this->dest->getX() - $this->orig->getX();
        $this->y = $this->dest->getY() - $this->orig->getY();
        $this->z = $this->dest->getZ() - $this->orig->getZ();
        $this->w = $this->dest->getW() - $this->orig->getW();

        if (self::$verbose == true) {
            printf(
                "Vector( x:%.2f, y:%.2f, z:%.2f, w:%.2f ) constructed\n",
                $this->getX(),
                $this->getY(),
                $this->getZ(),
                $this->getW()
            );
        }

        unset($this->orig);
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function getZ(): float
    {
        return $this->z;
    }

    public function getW(): float
    {
        return $this->w;
    }

    public function __toString(): string
    {
        return sprintf(
            "Vector( x:%.2f, y:%.2f, z:%.2f, w:%.2f )",
            $this->getX(),
            $this->getY(),
            $this->getZ(),
            $this->getW()
        );
    }

    public function magnitude(): float
    {
        return sqrt(pow($this->x, 2) + pow($this->y, 2) + pow($this->z, 2));
    }

    public function normalize(): Vector
    {
        if ($this->magnitude() == 1) {
            return clone $this;
        }

        $vertex = new Vertex(['x' => $this->x / $this->magnitude(), 'y' => $this->y / $this->magnitude(), 'z' => $this->z  / $this->magnitude()]);

        return new Vector(['dest' => $vertex]);
    }

    public function add(Vector $rhs): Vector
    {
        $vertex = new Vertex(['x' => $this->x + $rhs->getX(), 'y' => $this->y + $rhs->getY(), 'z' => $this->z + $rhs->getZ()]);

        unset($rhs);

        return new Vector(['dest' => $vertex]);
    }

    public function sub(Vector $rhs): Vector
    {
        $vertex = new Vertex(['x' => $this->x - $rhs->getX(), 'y' => $this->y - $rhs->getY(), 'z' => $this->z - $rhs->getZ()]);

        unset($rhs);

        return new Vector(['dest' => $vertex]);
    }

    public function opposite(): Vector
    {
        $vertex = new Vertex(['x' => $this->x * -1, 'y' => $this->y * -1, 'z' => $this->z * -1]);

        return new Vector(['dest' => $vertex]);
    }

    public function scalarProduct(int $k): Vector
    {
        $vertex = new Vertex(['x' => $this->x * $k, 'y' => $this->y * $k, 'z' => $this->z * $k]);

        return new Vector(['dest' => $vertex]);
    }

    public function dotProduct(Vector $rhs): float
    {
        return $this->x * $rhs->getX() + $this->y * $rhs->getY() + $this->z * $rhs->getZ();
    }

    public function cos(Vector $rhs): float
    {
        return $this->dotProduct($rhs) / ($this->magnitude() * $rhs->magnitude());
    }

    public function crossProduct(Vector $rhs): Vector
    {
        $vertex = new Vertex(['x' => $this->y * $rhs->getZ() - $this->z * $rhs->getY(), 'y' => $this->z * $rhs->getX() - $this->x * $rhs->getZ(), 'z' => $this->x * $rhs->getY() - $this->y * $rhs->getX()]);

        return new Vector(['dest' => $vertex]);
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        if (self::$verbose == true) {
            printf(
                "Vector( x:%.2f, y:%.2f, z:%.2f, w:%.2f ) destructed\n",
                $this->getX(),
                $this->getY(),
                $this->getZ(),
                $this->getW()
            );
        }
    }
}
