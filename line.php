<?php

use Waxer\Rasterix\Color;
use Waxer\Rasterix\Matrix;
use Waxer\Rasterix\Vector;
use Waxer\Rasterix\Vertex;

require_once '/srv/http/vendor/autoload.php';

const IMAGE_WIDTH = 890;
const IMAGE_HEIGHT = 890;

$cOrigin = new Color(['red' => 255, 'green' => 255, 'blue' => 255]);
$cXAxis = new Color(['red' => 255, 'green' => 0, 'blue' => 0]);
$cYAxis = new Color(['red' => 0, 'green' => 255, 'blue' => 0]);
$cZAxis = new Color(['red' => 0, 'green' => 0, 'blue' => 255]);

$xAxis = new Vertex( array( 'x' => 1, 'y' => 0, 'z' => -1, 'color' => $cXAxis ) );
$yAxis = new Vertex( array( 'x' => 0, 'y' => -10, 'z' => -1, 'color' => $cYAxis ) );
$zAxis = new Vertex( array( 'x' => 0, 'y' => 0, 'z' => -1, 'color' => $cZAxis ) );
$origin = new Vertex( array( 'x' => 0, 'y' => 0, 'z' => -1, 'color' => $cOrigin ) );

$image = imagecreatetruecolor(IMAGE_WIDTH, IMAGE_HEIGHT);

$cOrigin = $origin->getColor()->allocateGDColor($image);
$cXAxis = $xAxis->getColor()->allocateGDColor($image);
$cYAxis = $yAxis->getColor()->allocateGDColor($image);
$cZAxis = $zAxis->getColor()->allocateGDColor($image);

$xAxis = Vertex::projectPoint3($xAxis);
var_dump($xAxis);
die();

/* $yAxis = Vertex::projectPoint3($yAxis); */
/* $zAxis = Vertex::projectPoint3($zAxis); */
/* $origin = Vertex::projectPoint3($origin); */

/* imageline($image, 0, 0, $xAxis->getX(), $xAxis->getY(), $cXAxis); */
//imageline($image, 0, 0, $yAxis->getX(), $yAxis->getY(), $cYAxis);
/* imageline($image, 0, 0, $zAxis->getX(), $zAxis->getY(), $cZAxis); */

imagesetpixel($image, $xAxis->getX(), $xAxis->getY(), $cXAxis);

header('Content-type: image/png');

imagepng($image);
imagedestroy($image);

