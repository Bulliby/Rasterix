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

if (!isset($_SESSION['corners'])) {
    $_SESSION['corners'] = $corners;
}

$cameraToWorld = new Matrix( array( 'preset' => Matrix::IDENTITY ));
$M = new Matrix( array( 'preset' => Matrix::IDENTITY ));

$xTranslation = $_POST['x-translation'] ?? null;
$yTranslation = $_POST['y-translation'] ?? null;
$zTranslation = $_POST['z-translation'] ?? null;
$zRotation = $_POST['z-rotation'] ?? null;
$yRotation = $_POST['y-rotation'] ?? null;
$xRotation = $_POST['x-rotation'] ?? null;

if ($xTranslation) {
    $vtx = new Vertex(['x' => $xTranslation, 'y' => 0, 'z' => 0]);
    $vtc = new Vector(['dest' => $vtx]);
    $M = new Matrix( array( 'preset' => Matrix::TRANSLATION, 'vtc' => $vtc) );
}

if ($yTranslation) {
    $vtx = new Vertex(['x' => 0, 'y' => $yTranslation, 'z' => 0]);
    $vtc = new Vector(['dest' => $vtx]);
    $M = new Matrix( array( 'preset' => Matrix::TRANSLATION, 'vtc' => $vtc) );
}

if ($zTranslation) {
    $vtx = new Vertex(['x' => 0, 'y' => 0, 'z' => $zTranslation]);
    $vtc = new Vector(['dest' => $vtx]);
    $M = new Matrix( array( 'preset' => Matrix::TRANSLATION, 'vtc' => $vtc) );
}

if ($zRotation) {
    $M = new Matrix( array( 'preset' => Matrix::RZ, 'angle' => $zRotation) );
}

if ($yRotation) {
    $M = new Matrix( array( 'preset' => Matrix::RY, 'angle' => $yRotation) );
}

if ($xRotation) {
    $M = new Matrix( array( 'preset' => Matrix::RX, 'angle' => $xRotation) );
}

$cameraToWorld = $cameraToWorld->multMatrix($M);

foreach ($_SESSION['corners'] as &$corner) 
{
    $corner = $cameraToWorld->transformVertex($corner); 
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
