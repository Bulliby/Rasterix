<?php

namespace Waxer\Rasterix;


class Image
{
    private int $imageWidth = 0;
    private int $imageHeight = 0;

    public function __construct(
        private int $xTranslation,
        private int $yTranslation,
        private float $xRotation,
        private float $yRotation,
        private float $zRotation,
        private int $scale,
    ){
    }

    public function __set(string $name, mixed $value): void
    {
        if (!isset($this->{$name}))
            throw new \Exception('Bad set argument');

        $this->{$name} = $value;
    }

    public function __get(string $name): mixed
    {
        if (!isset($this->{$name}))
            throw new \Exception('Bad get argument');

        return $this->{$name};
    }

    /**
     * @return array<int|float>
     */
    public function toArray(): array
    {
        return [
            'x-translation' => $this->xTranslation, 
            'y-translation' => $this->yTranslation, 
            'x-rotation' => $this->xRotation,
            'y-rotation' => $this->yRotation, 
            'z-rotation' => $this->zRotation,
            'scale' => $this->scale,
            'scale' => $this->scale,
            'imageWidth' => $this->imageWidth,
            'imageHeight' => $this->imageHeight,
        ];
    }
}

