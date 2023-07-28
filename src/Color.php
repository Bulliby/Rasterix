<?php

namespace Waxer\Rasterix;

class Color
{
    public int $red;
    public int $green;
    public int $blue;
    public static bool $verbose = false;

    /**
     * @param array<float|int> $rgb
     */
    public function __construct(array $rgb)
    {
        if (true === array_key_exists('rgb', $rgb)) {
            $this->red = (int) ($rgb['rgb'] & 0xFF0000) >> 16;
            $this->green = (int) ($rgb['rgb'] & 0xFF00) >> 8;
            $this->blue = (int) ($rgb['rgb'] & 0xFF);
        } elseif (true === array_key_exists('red', $rgb)
            && array_key_exists('green', $rgb)
            && array_key_exists('blue', $rgb)
        ) {
            $this->red = (int) $rgb['red'];
            $this->green = (int) $rgb['green'];
            $this->blue = (int) $rgb['blue'];
        } else {
            throw new InvalidArgumentsException();
        }

        if (self::$verbose == true) {
            echo sprintf("Color( red: %3d, green: %3d, blue: %3d ) constructed.", $this->red, $this->green, $this->blue).PHP_EOL;
        }
    }

    public function add(Color $color): self
    {
        return new self([
            'red' => (($this->red + $color->red) > 255) ? 255 : $this->red + $color->red,
            'green' => (($this->green + $color->green) > 255) ? 255 : $this->green + $color->green,
            'blue' => (($this->blue + $color->blue) > 255) ? 255 : $this->blue + $color->blue,
        ]);
    }

    public function sub(Color $color): self
    {
        return new self([
            'red' => (($this->red - $color->red) < 0) ? 0 : $this->red - $color->red,
            'green' => (($this->green - $color->green) < 0) ? 0 : $this->green - $color->green,
            'blue' => (($this->blue - $color->blue) < 0) ? 0 : $this->blue - $color->blue,
        ]);
    }

    public function mult(float $color): self
    {
        return new self([
            'red' => (($this->red * $color) > 255) ? 255 : $this->red * $color,
            'green' => (($this->green * $color) > 255) ? 255 : $this->green * $color,
            'blue' => (($this->blue * $color) > 255) ? 255 : $this->blue * $color,
        ]);
    }

    public function __toString(): string
    {
        return sprintf("Color( red: %3d, green: %3d, blue: %3d )", $this->red, $this->green, $this->blue);
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        if (self::$verbose == true) {
            echo sprintf("Color( red: %3d, green: %3d, blue: %3d ) destructed.", $this->red, $this->green, $this->blue).PHP_EOL;
        }
    }

    public function allocateGDColor(\GdImage $image): int|false
    {
        return imagecolorallocate($image, $this->red, $this->green, $this->blue);
    }
}
