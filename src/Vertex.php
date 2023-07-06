<?php

namespace Waxer\Rasterix;

class Vertex
{
    private float $x;
    private float $y;
    private float $z;
    private float $w = 1;
    private Color $color;
    public static bool $verbose = false;


    /**
     * @param array{x?: float, y?: float, z?: float, w?: float, color?: Color} $args
     */
    public function __construct(array $args)
    {
        $this->x = $args['x'] ?? 0;
        $this->y = $args['y'] ?? 0;
        $this->z = $args['z'] ?? 0;
        $this->w = $args['w'] ?? 1;

        $this->color = $args['color'] ?? new Color(array('rgb' => (PHP_INT_MAX & 0xFFFFFF)));

        if (self::$verbose == true) {
            vprintf(
                'Vertex( x: %.2f, y: %.2f, z:%.2f, w:%.2f, Color( red: %3d, green: %3d, blue: %3d ) ) constructed'.PHP_EOL,
                $this->getParam()
            );
        }
    }

    /**
     * @return void
     */
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
            $this->x,
            $this->y,
            $this->z,
            $this->w,
            $this->color->red,
            $this->color->green,
            $this->color->blue
        ];
    }

    /**
     * Perspective divide or z divide
     */
    public static function projectPoint(Vertex $point): Vertex
    {
        $x_proj = $point->getX() / - $point->getZ();
        $y_proj = $point->getY() / - $point->getZ();
        $x_NDC = $x_proj / 2;
        $y_NDC = $y_proj / 2;
        $x_rast = $x_NDC * IMAGE_WIDTH;
        $y_rast = $y_NDC * IMAGE_HEIGHT;
        
        return new Vertex( array( 'x' => $x_rast, 'y' => $y_rast, 'color' => $point->getColor() ) );
    }

    /**
     * Perspective divide or z divide.
     * Here we consider that canvas is at 1 unit from the "eye"
     * We need other type of computation if we want something else
     * Projection matr.x.
     * We assume that the canva has a size of 2.
     */
    public static function projectPoint3(Vertex $point): Vertex
    {
        $x_proj = $point->getX() / - $point->getZ();
        $y_proj = $point->getY() / - $point->getZ();

        $x_NDC = ($x_proj + 2 / 2) / 2;
        $y_NDC = ($y_proj + 2 / 2) / 2;
        $x_rast = floor($x_NDC * IMAGE_WIDTH);
        $y_rast = floor((1 - $y_NDC) * IMAGE_HEIGHT);
        
        return new Vertex( array( 'x' => $x_rast, 'y' => $y_rast, 'color' => $point->getColor() ) );
    }

    /**
     * @return array<int, float>
     */
    public function toArray(): array
    {
        return [$this->x, $this->y, $this->z, $this->w];
    }

    /**
     * @param array<float> $array
     */
    public static function toVertex(array $array): self
    {
        return new Vertex(['x' => $array[0], 'y' => $array[1], 'z' => $array[2], 'w' => 1]);
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

    public function getColor(): Color
    {
        return $this->color;
    }

    public function setX(float $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function setY(float $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function setZ(float $z): self
    {
        $this->z = $z;

        return $this;
    }

    public function setW(float $w): self
    {
        $this->w = $w;

        return $this;
    }

    public function setColor(Color $color): self
    {
        $this->color = $color;

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
                $this->x,
                $this->y,
                $this->z,
                $this->w
            );
        }
    }
}
