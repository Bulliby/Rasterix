<?php

class Color
{
	public $red;
	public $green;
	public $blue;
	static $verbose = FALSE;

	public function __construct(array $rgb)
	{
		if (isset($rgb['rgb'])) {
			$this->red = (int)($rgb['rgb'] & 0xFF0000) >> 16;
			$this->green = (int)($rgb['rgb'] & 0xFF00) >> 8;
			$this->blue = (int)($rgb['rgb'] & 0xFF);
		} else if (isset($rgb['red']) && isset($rgb['green']) && isset($rgb['blue'])) {
            $this->red = (int)$rgb['red'];
            $this->green = (int)$rgb['green'];
            $this->blue = (int)$rgb['blue'];
		} else {
            throw new \Exception("Array (rgb) not well formated");
        }

		if (self::$verbose == TRUE) {
            echo sprintf("Color( red: %3d, green: %3d, blue: %3d ) constructed.", $this->red, $this->green, $this->blue).PHP_EOL;
        }
	}

	public function add($color)
	{
        return new self([
            'red' => (($this->red + $color->red) > 255) ? 255 : $this->red + $color->red, 
            'green' => (($this->green + $color->green) > 255) ? 255 : $this->green + $color->green, 
            'blue' => (($this->blue + $color->blue) > 255) ? 255 : $this->blue + $color->blue,
        ]);
	}

	public function sub($color)
	{
        return new self([
            'red' => (($this->red - $color->red) < 0) ? 0 : $this->red - $color->red, 
            'green' => (($this->green - $color->green) < 0) ? 0 : $this->green - $color->green, 
            'blue' => (($this->blue - $color->blue) < 0) ? 0 : $this->blue - $color->blue,
        ]);
	}

	public function mult($color)
	{
        return new self([
            'red' => (($this->red * $color) > 255) ? 255 : $this->red * $color, 
            'green' => (($this->green * $color) > 255) ? 255 : $this->green * $color, 
            'blue' => (($this->blue * $color) > 255) ? 255 : $this->blue * $color,
        ]);
	}

	static public function doc(){
		$filecontents = file_get_contents("Color.doc.txt");
		print($filecontents);
	}

	public function __toString(){
        return sprintf("Color( red: %3d, green: %3d, blue: %3d )", $this->red, $this->green, $this->blue);
	}

	public function __destruct(){
		if (self::$verbose == TRUE) {
            echo sprintf("Color( red: %3d, green: %3d, blue: %3d ) destructed.", $this->red, $this->green, $this->blue).PHP_EOL;
        }
	}
}
