<?php



require_once 'Color.class.php';
require_once 'Vertex.class.php';
require_once 'Vector.class.php';

class Matrix
{
    const IDENTITY = "IDENTITY";
    const SCALE = "SCALE";
    const RX = "RX";
    const RY = "RY";
    const RZ = "RZ";
    const TRANSLATION = "TRANSLATION";
    const PROJECTION = "PROJECTION";

    const SIZE = 4;

    private float $_scale;
    // Radian
    private float $_angle;
    private Vector $_vtc;

    // Projection
    private float $_ratio;
    private float $_near;
    private float $_far;

    private array $_matrix = array();

	static bool $verbose = false;

	public function __construct(array $args)
	{
        if (!isset($args['preset'])) 
            throw new \Exception('Preset is mandatory');
        
        switch ($args['preset']) 
        {
            case self::IDENTITY:
                $this->createIdentityMatrix();
            break;
            case self::SCALE:
                if (!isset($args['scale'])) 
                    $this->throwParameterMissingException();
                $this->_scale = $args['scale'];
            break;
            case self::RX:
            case self::RY:
            case self::RZ:
                if (!isset($args['angle'])) 
                    $this->throwParameterMissingException();
                $this->_angle = $args['angle'];
            break;
            case self::TRANSLATION:
                if (!isset($args['vtc'])) 
                    $this->throwParameterMissingException();
                $this->_vtc = $args['vtc'];
                $this->createTraslationMatrix($this->_vtc);
            break;
            case self::PROJECTION:
                if (!isset($args['fov']) && !isset($args['ratio']) && !isset($args['near']) && !isset($args['far']))
                    $this->throwParameterMissingException();
                $this->_ratio = $args['ratio'];
                $this->_near = $args['near'];
                $this->_far = $args['far'];
            break;
            default:
                throw new \Exception('Preset not defined');
        }

	}

    private function createTraslationMatrix(Vector $vtc)
    {
        
    }

    private function throwParameterMissingException()
    {
        throw new \Exception('Some parameters are missing');
    }

    private function createIdentityMatrix()
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

    private function outputMatrix(): string
    {
        $output = "M | vtcX | vtcY | vtcZ | vtxO\n-----------------------------\n";

        $lineX = ""; $lineY = ""; $lineZ = ""; $lineO = "";
        
        for($x = 0; $x != self::SIZE; $x++)
        {
            $lineX .= sprintf("%.2f | ", $this->_matrix[$x]->getX());
            $lineY .= sprintf("%.2f | ", $this->_matrix[$x]->getY());
            $lineZ .= sprintf("%.2f | ", $this->_matrix[$x]->getZ());
            $lineO .= sprintf("%.2f | ", $this->_matrix[$x]->getW());
        }

        return sprintf("%sx | %s\ny | %s\nz | %s\nw | %s\n", $output, $lineX, $lineY, $lineZ, $lineO);
    }

	public function __destruct()
	{
	}

    static public function doc()
    {
		$filecontents = file_get_contents("Matrix.doc.txt");
		print($filecontents);
	}
	
    public function __toString()
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

?>
