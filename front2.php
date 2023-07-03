<?php

use Waxer\Rasterix\Color;
use Waxer\Rasterix\Matrix;
use Waxer\Rasterix\Vector;
use Waxer\Rasterix\Vertex;

require_once '/srv/http/vendor/autoload.php';

$color = new Color(['red' => 255, 'green' => 0, 'blue' => 0]);

session_start();

const IMAGE_WIDTH = 890;
const IMAGE_HEIGHT = 890;

$corner1 = new Vertex( array( 'x' => 1, 'y' => -1, 'z' => -5, 'color' => $color ) );
$corner2 = new Vertex( array( 'x' => 1, 'y' => -1, 'z' => -3, 'color' => $color ) );
$corner3 = new Vertex( array( 'x' => 1, 'y' => 1, 'z' => -5, 'color' => $color ) );
$corner4 = new Vertex( array( 'x' => 1, 'y' => 1, 'z' => -3, 'color' => $color ) );
$corner5 = new Vertex( array( 'x' => -1, 'y' => -1, 'z' => -5, 'color' => $color ) );
$corner6 = new Vertex( array( 'x' => -1, 'y' => -1, 'z' => -3, 'color' => $color ) );
$corner7 = new Vertex( array( 'x' => -1, 'y' => 1, 'z' => -5, 'color' => $color ) );
$corner8 = new Vertex( array( 'x' => -1, 'y' => 1, 'z' => -3, 'color' => $color ) );

$corners = [$corner1, $corner2, $corner3, $corner4, $corner5, $corner6, $corner7, $corner8];

$M = new Matrix( array( 'preset' => Matrix::IDENTITY ));

$_SESSION['x-translation'] += $_POST['x-translation'] ?? 0;
$_SESSION['y-translation'] += $_POST['y-translation'] ?? 0;
$_SESSION['z-translation'] += $_POST['z-translation'] ?? 0;
$_SESSION['z-rotation'] += $_POST['z-rotation'] ?? 0;
$_SESSION['y-rotation'] += $_POST['y-rotation'] ?? 0;
$_SESSION['x-rotation'] += $_POST['x-rotation'] ?? 0;

$vtx = new Vertex(['x' => $_SESSION['x-translation'], 'y' => $_SESSION['y-translation'], 'z' => $_SESSION['z-translation']]);
$vtc = new Vector(['dest' => $vtx]);
$T = new Matrix(['preset' => Matrix::TRANSLATION, 'vtc' => $vtc]);
$RX = new Matrix(['preset' => Matrix::RX, 'angle' => $_SESSION['x-rotation']]);
$RY = new Matrix(['preset' => Matrix::RY, 'angle' => $_SESSION['y-rotation']]);
$RZ = new Matrix(['preset' => Matrix::RZ, 'angle' => $_SESSION['z-rotation']]);

$to = new Vertex( array( 'x' => $corners[0]->getX(), 'y' => $corners[0]->getY(), 'z' => $corners[0]->getZ(), 'color' => $color ) );
$from = new Vertex(['x' => 1, 'y' => 1, 'z' => 10]);
$cameraToWorld = new Matrix( array( 'preset' => Matrix::CAMERATOWORLD , 'from' => $from, 'to' => $to));
$projectedCorners = [];

foreach ($corners as &$corner) 
{
    $corner = $cameraToWorld->transformVertex($corner);
    $corner = $M->multMatrix($T)->multMatrix($RX)->multMatrix($RY)->multMatrix($RZ)->transformVertex($corner);
    $worldToCamera = new Matrix( array( 'preset' => Matrix::INVERSE , 'matrix' => $cameraToWorld));
    $corner = $worldToCamera->transformVertex($corner); 
    $projectedCorners [] = Vertex::projectPoint($corner);
}

$image = imagecreatetruecolor(IMAGE_WIDTH, IMAGE_HEIGHT);
$col_poly = imagecolorallocate($image, $color->red, $color->green, $color->blue);

imagepolygon($image, [
    $projectedCorners[5]->getX(), $projectedCorners[5]->getY(),
    $projectedCorners[1]->getX(), $projectedCorners[1]->getY(),
    $projectedCorners[3]->getX(), $projectedCorners[3]->getY(),
    $projectedCorners[7]->getX(), $projectedCorners[7]->getY(),
], 4, $col_poly);

imagepolygon($image, [
    $projectedCorners[5]->getX(), $projectedCorners[5]->getY(),
    $projectedCorners[4]->getX(), $projectedCorners[4]->getY(),
    $projectedCorners[6]->getX(), $projectedCorners[6]->getY(),
    $projectedCorners[7]->getX(), $projectedCorners[7]->getY(),
], 4, $col_poly);

imagepolygon($image, [
    $projectedCorners[5]->getX(), $projectedCorners[5]->getY(),
    $projectedCorners[4]->getX(), $projectedCorners[4]->getY(),
    $projectedCorners[6]->getX(), $projectedCorners[6]->getY(),
    $projectedCorners[7]->getX(), $projectedCorners[7]->getY(),
], 4, $col_poly);

imagepolygon($image, [
    $projectedCorners[4]->getX(), $projectedCorners[4]->getY(),
    $projectedCorners[0]->getX(), $projectedCorners[0]->getY(),
    $projectedCorners[2]->getX(), $projectedCorners[2]->getY(),
    $projectedCorners[6]->getX(), $projectedCorners[6]->getY(),
], 4, $col_poly);

imagepolygon($image, [
    $projectedCorners[0]->getX(), $projectedCorners[0]->getY(),
    $projectedCorners[1]->getX(), $projectedCorners[1]->getY(),
    $projectedCorners[3]->getX(), $projectedCorners[3]->getY(),
    $projectedCorners[2]->getX(), $projectedCorners[2]->getY(),
], 4, $col_poly);


header('Content-type: image/png');

imagepng($image);
imagedestroy($image);
