<?php

require_once 'Color.class.php';
require_once 'Vertex.class.php';

class Vector
{
	private float $_x;
	private	float $_y;
	private float $_z;
	private float $_w = 0.0;
	private Vertex $dest;
	private Vertex $orig;

	static bool $verbose = false;

	public function __construct(array $args)
	{
        if (!isset($args['dest'])) 
            throw new \Exception('Dest vertex is mandatory');
        $this->dest = $args['dest'];
        $this->orig = (isset($args['orig'])) ? $args['orig'] : new Vertex([]); 

        $this->_x = $this->dest->getX() - $this->orig->getX();
        $this->_y = $this->dest->getY() - $this->orig->getY();
        $this->_z = $this->dest->getZ() - $this->orig->getZ();
        $this->_w = $this->dest->getW() - $this->orig->getW();
        
        if (self::$verbose == true) 
        {
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

	static public function doc(){
		$filecontents = file_get_contents("Vector.doc.txt");
		print($filecontents);
	}

	public function __toString(){
        if (self::$verbose == true) 
        {
            return sprintf(
                "Vector( x:%.2f, y:%.2f, z:%.2f, w:%.2f )", 
                $this->getX(), 
                $this->getY(),
                $this->getZ(), 
                $this->getW()
            );
        }
	}

    public function magnitude(): float
    {
        return sqrt(pow($this->_x, 2) + pow($this->_y, 2) + pow($this->_z, 2));
	}

    public function normalize(): Vector
    {
        if ($this->magnitude() == 1)
            return clone $this;

        $vertex = new Vertex(['x' => $this->_x / $this->magnitude(), 'y' => $this->_y / $this->magnitude(), 'z' => $this->_z  / $this->magnitude(), 'w' => $this->_w]);

        return new Vector(['dest' => $vertex]);
	}

    public function add(Vector $rhs): Vector
    {
        $vertex = new Vertex(['x' => $this->_x + $rhs->getX(), 'y' => $this->_y + $rhs->getY(), 'z' => $this->_z + $rhs->getZ()]);
        
        unset($rhs);

        return new Vector(['dest' => $vertex]);
    }

    public function sub(Vector $rhs): Vector
    {
        $vertex = new Vertex(['x' => $this->_x - $rhs->getX(), 'y' => $this->_y - $rhs->getY(), 'z' => $this->_z - $rhs->getZ()]);
        
        unset($rhs);

        return new Vector(['dest' => $vertex]);
    }

    public function opposite(): Vector
    {
        $vertex = new Vertex(['x' => $this->_x * -1, 'y' => $this->_y * -1, 'z' => $this->_z * -1]);

        return new Vector(['dest' => $vertex]);
    }

    public function scalarProduct(int $k): Vector
    {
        $vertex = new Vertex(['x' => $this->_x * $k, 'y' => $this->_y * $k, 'z' => $this->_z * $k]);

        return new Vector(['dest' => $vertex]);
    }

    public function dotProduct(Vector $rhs): float
    {
        return $this->_x * $rhs->getX() + $this->_y * $rhs->getY() + $this->_z * $rhs->getZ();
    }

    public function cos(Vector $rhs): float
    {
        return $this->dotProduct($rhs) / ($this->magnitude() * $rhs->magnitude());
    }

    public function crossProduct(Vector $rhs): Vector
    {
        $vertex = new Vertex(['x' => $this->_y * $rhs->getZ() - $this->_z * $rhs->getY(), 'y' => $this->_z * $rhs->getX() - $this->_x * $rhs->getZ(), 'z' => $this->_x * $rhs->getY() - $this->_y * $rhs->getX()]);

        return new Vector(['dest' => $vertex]);
    }

	public function __destruct()
	{
        if (self::$verbose == true) 
        {
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
