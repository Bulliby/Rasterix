<?php

namespace Waxer\Rasterix;

class Vertex
{
    private float $_x;
    private float $_y;
    private float $_z;
    private float $_w = 1.0;
    private Color $_color;
    public static bool $verbose = false;


    /**
     * @param array{x?: float, y?: float, z?: float, w?: float, color?: Color} $args
     */
    public function __construct(array $args)
    {
        $this->_x = (array_key_exists('x', $args)) ? (float) $args['x'] : 0;
        $this->_y = (array_key_exists('y', $args)) ? (float) $args['y'] : 0;
        $this->_z = (array_key_exists('z', $args)) ? (float) $args['z'] : 0;
        $this->_w = (array_key_exists('w', $args)) ? (float) $args['w'] : 1.0;

        $this->_color = (array_key_exists('color', $args)) ? $args['color'] : new Color(array('rgb' => (PHP_INT_MAX & 0xFFFFFF)));

        if (self::$verbose == true) {
            vprintf(
                'Vertex( x: %.2f, y: %.2f, z:%.2f, w:%.2f, Color( red: %3d, green: %3d, blue: %3d ) ) constructed'.PHP_EOL,
                $this->getParam()
            );
        }
    }

    public function __destruct()
    {
        if (self::$verbose === true) {
            vprintf(
                'Vertex( x: %.2f, y: %.2f, z:%.2f, w:%.2f, Color( red: %3d, green: %3d, blue: %3d ) ) destructed'.PHP_EOL,
                $this->getParam()
            );
        }
    }

    /**
     * @return array<int, float>
     */
    private function getParam(): array
    {
        return [
            $this->_x,
            $this->_y,
            $this->_z,
            $this->_w,
            $this->_color->red,
            $this->_color->green,
            $this->_color->blue
        ];
    }

    public function getX(): float
    {
        return $this->_x;
    }

    public function getY(): float
    {
        return $this->_y;
    }

    public function getZ(): float
    {
        return $this->_z;
    }

    public function getW(): float
    {
        return $this->_w;
    }

    public function getColor(): Color
    {
        return $this->_color;
    }

    public function setX(float $x): self
    {
        $this->_x = $x;

        return $this;
    }

    public function setY(float $y): self
    {
        $this->_y = $y;

        return $this;
    }

    public function setZ(float $z): self
    {
        $this->_z = $z;

        return $this;
    }

    public function setW(float $w): self
    {
        $this->_w = $w;

        return $this;
    }

    public function setColor(Color $color): self
    {
        $this->_color = $color;

        return $this;
    }

    public function __toString(): string
    {
        if (self::$verbose === true) {
            return	vsprintf(
                'Vertex( x: %.2f, y: %.2f, z:%.2f, w:%.2f, Color( red: %3d, green: %3d, blue: %3d ) )',
                $this->getParam()
            );
        } else {
            return	sprintf(
                'Vertex( x: %.2f, y: %.2f, z:%.2f, w:%.2f )',
                $this->_x,
                $this->_y,
                $this->_z,
                $this->_w
            );
        }
    }
}
