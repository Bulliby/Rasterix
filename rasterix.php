<?php

use Waxer\Rasterix\Color;
use Waxer\Rasterix\Matrices\Matrix;
use Waxer\Rasterix\Vertex;
use Waxer\Rasterix\Vector;
use Waxer\Rasterix\Enums\MatrixType;

require_once '/srv/http/vendor/autoload.php';

session_start();

$color = new Color(['red' => 255, 'green' => 0, 'blue' => 0]);
$centerColor = new Color(['red' => 255, 'green' => 255, 'blue' => 255]);

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
$center = new Vertex( array( 'x' => 0, 'y' => 0, 'z' => -4, 'color' => $centerColor) );

$corners = [$corner1, $corner2, $corner3, $corner4, $corner5, $corner6, $corner7, $corner8, $center];

$M = new Matrix(MatrixType::Identity);

if (empty($_SESSION)) {
    $_SESSION['x-translation'] = 250;
    $_SESSION['y-translation'] = 250;
    $_SESSION['z-translation'] = 1;
    $_SESSION['x-rotation'] = 0;
    $_SESSION['y-rotation'] = 0;
    $_SESSION['z-rotation'] = 0;
}

$_SESSION['x-translation'] = $_POST['x-translation'] ?? $_SESSION['x-translation'];
$_SESSION['y-translation'] = $_POST['y-translation'] ?? $_SESSION['y-translation'];
$_SESSION['z-translation'] = $_POST['z-translation'] ?? $_SESSION['z-translation'];
$_SESSION['z-rotation'] = $_POST['z-rotation'] ?? $_SESSION['x-rotation'];
$_SESSION['y-rotation'] = $_POST['y-rotation'] ?? $_SESSION['y-rotation'];
$_SESSION['x-rotation'] = $_POST['x-rotation'] ?? $_SESSION['z-rotation'];



$S  = new Matrix(MatrixType::Scale, 40.0);
//$vtx = new Vertex( array( 'x' => 445, 'y' => 445, 'z' => 1 ) );
//$vtx = new Vertex(['x' => (float) $_SESSION['x-translation'], 'y' => (float) $_SESSION['y-translation'], 'z' => (float) $_SESSION['z-translation']]);
$vtx = new Vertex(['x' => (float) $_SESSION['x-translation'], 'y' => (float) $_SESSION['y-translation'], 'z' => (float) $_SESSION['z-translation']]);
$vtc = new Vector( array( 'dest' => $vtx ) );
$T = new Matrix(MatrixType::Translation, $vtc);
$RX = new Matrix(MatrixType::RX, (float) $_SESSION['x-rotation']);
$RY = new Matrix(MatrixType::RY, (float) $_SESSION['y-rotation']);
$RZ = new Matrix(MatrixType::RZ, (float) $_SESSION['z-rotation']);

$worldToCenter = new Matrix(MatrixType::Custom, $center);
$centerToWorld = new Matrix(MatrixType::Inverse, $worldToCenter);

$from = new Vertex(['x' => 0, 'y' => 0, 'z' => 0]);
$to = new Vertex( array( 'x' => 445, 'y' => 445, 'z' => -445, 'color' => $color ) );
$cameraToWorld = new Matrix(MatrixType::View, $from, $to);
$worldToCamera = new Matrix(MatrixType::Inverse, $cameraToWorld);
$projection = new Matrix(MatrixType::Projection, 1, 1.0, -50.0, 60);

foreach ($corners as &$corner) 
{
    $corner = $centerToWorld->transformVertex($corner); 
    $corner = $RY->multMatrix($RZ)->multMatrix($RX)->transformVertex($corner);
    //$corner = $worldToCamera->transformVertex($corner);
    //apply them in the reverse order of the desired transformations
    $corner = $T->multMatrix($S)->transformVertex($corner);
    $projectedCorners [] = $projection->transformVertex($corner);
}

$image = imagecreatetruecolor(IMAGE_WIDTH, IMAGE_HEIGHT);
$col_poly = imagecolorallocate($image, $color->red, $color->green, $color->blue);
$col_center = imagecolorallocate($image, $center->getColor()->red, $center->getColor()->green, $center->getColor()->blue);
$downColor = new Color(['red' => 102, 'green' => 252, 'blue' => 102]);
$downColor = imagecolorallocate($image, $downColor->red, $downColor->green, $downColor->blue);
$topColor = new Color(['red' => 252, 'green' => 186, 'blue' => 3]);
$topColor = imagecolorallocate($image, $topColor->red, $topColor->green, $topColor->blue);

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
    $projectedCorners[4]->getX(), $projectedCorners[4]->getY(),
    $projectedCorners[0]->getX(), $projectedCorners[0]->getY(),
    $projectedCorners[2]->getX(), $projectedCorners[2]->getY(),
    $projectedCorners[6]->getX(), $projectedCorners[6]->getY(),
], 4, $col_poly);

imagepolygon($image, [
    $projectedCorners[5]->getX(), $projectedCorners[5]->getY(),
    $projectedCorners[4]->getX(), $projectedCorners[4]->getY(),
    $projectedCorners[6]->getX(), $projectedCorners[6]->getY(),
    $projectedCorners[7]->getX(), $projectedCorners[7]->getY(),
], 4, $topColor);

imagepolygon($image, [
    $projectedCorners[0]->getX(), $projectedCorners[0]->getY(),
    $projectedCorners[1]->getX(), $projectedCorners[1]->getY(),
    $projectedCorners[3]->getX(), $projectedCorners[3]->getY(),
    $projectedCorners[2]->getX(), $projectedCorners[2]->getY(),
], 4, $downColor);

imagesetpixel($image, $projectedCorners[8]->getX(), $projectedCorners[8]->getY(), $col_center);

header('Content-type: image/png');

imagepng($image);
imagedestroy($image);
