
<?php

require_once 'Color.class.php';

class Vertex
{
	private float $_x;
	private float $_y;
	private float $_z;
	private float $_w = 1.0;
	private Color $_color;
	static bool $verbose = FALSE;

	public function __construct(array $args)
	{
		$this->_x = (isset($args['x'])) ? $args['x'] : 0;
		$this->_y = (isset($args['y'])) ? $args['y'] : 0;
		$this->_z = (isset($args['z'])) ? $args['z'] : 0;
        $this->_w = (isset($args['w'])) ? $args['w'] : 1.0;
		$this->_color = (isset($args['color'])) ? $args['color'] : new Color(array('rgb' => (PHP_INT_MAX & 0xFFFFFF)));

		if (self::$verbose == true)
		{
			vprintf(
				'Vertex( x: %.2f, y: %.2f, z:%.2f, w:%.2f, Color( red: %3d, green: %3d, blue: %3d ) ) constructed'.PHP_EOL,
				$this->getParam()
			);
		}
	}

	public function __destruct()
	{
		if (self::$verbose === TRUE)
		{
			vprintf(
				'Vertex( x: %.2f, y: %.2f, z:%.2f, w:%.2f, Color( red: %3d, green: %3d, blue: %3d ) ) destructed'.PHP_EOL,
				$this->getParam()
			);
		}
	}

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

	public function getX()
	{
		return $this->_x;
	}

	public function getY()
	{
		return $this->_y;
	}

	public function getZ()
	{
		return $this->_z;
	}

	public function getW()
	{
		return $this->_w;
	}

	public function getColor()
	{
		return $this->_color;
	}

    public function setX($x)
    {
		$this->_x = $x;

        return $this;
	}
    public function setY($y)
    {
		$this->_y = $y;

        return $this;
	}

    public function setZ($z)
    {
		$this->_z = $z;

        return $this;
	}

    public function setW($w)
    {
		$this->_w = $w;

        return $this;
	}

    public function setColor($color)
    {
		$this->_color = $color;

        return $this;
	}
	
    static public function doc()
    {
		$filecontents = file_get_contents("Vertex.doc.txt");
		print($filecontents);
	}
	
    public function __toString()
    {
        if (self::$verbose === TRUE) 
        {
            return	vsprintf(
                'Vertex( x: %.2f, y: %.2f, z:%.2f, w:%.2f, Color( red: %3d, green: %3d, blue: %3d ) )',
                $this->getParam()
            );
        }
		else
        {
            return	sprintf(
                'Vertex( x: %.2f, y: %.2f, z:%.2f, w:%.2f )',
                $this->_x, $this->_y, $this->_z, $this->_w
            );
        }
	}
}

?>
