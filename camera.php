<?php

/**
 * Here we move the camera around the object.
 */

use Waxer\Rasterix\Color;
use Waxer\Rasterix\Matrix;
use Waxer\Rasterix\Vertex;
use Waxer\Rasterix\Vector;

require_once '/srv/http/vendor/autoload.php';

$color = new Color(['red' => 255, 'green' => 0, 'blue' => 0]);

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

session_start();

if (empty($_SESSION)) {
    $_SESSION['from'] = new Vertex(['x' => 0, 'y' => 0, 'z' => -20]);
}

if (isset($_POST['x-translation'])) {
    $vtx = new Vertex(['x' => $_POST['x-translation'], 'y' => 0, 'z' => 0]);
    $vtc = new Vector(['dest' => $vtx]);
    $M = new Matrix( array( 'preset' => Matrix::TRANSLATION, 'vtc' => $vtc) );
    $_SESSION['from'] = $M->transformVertex($_SESSION['from']);
}
if (isset($_POST['y-translation'])) {
    $vtx = new Vertex(['x' => 0, 'y' => $_POST['y-translation'], 'z' => 0]);
    $vtc = new Vector(['dest' => $vtx]);
    $M = new Matrix( array( 'preset' => Matrix::TRANSLATION, 'vtc' => $vtc) );
    $_SESSION['from'] = $M->transformVertex($_SESSION['from']);
}
if (isset($_POST['z-translation'])) {
    $vtx = new Vertex(['x' => 0, 'y' => 0, 'z' => $_POST['z-translation']]);
    $vtc = new Vector(['dest' => $vtx]);
    $M = new Matrix( array( 'preset' => Matrix::TRANSLATION, 'vtc' => $vtc) );
    $_SESSION['from'] = $M->transformVertex($_SESSION['from']);
}

if (isset($_POST['x-rotation'])) {
    $M = new Matrix( array( 'preset' => Matrix::RX, 'angle' => (float) $_POST['x-rotation']) );
    $_SESSION['from'] = $M->transformVertex($_SESSION['from']);
}
if (isset($_POST['y-rotation'])) {
    $M = new Matrix( array( 'preset' => Matrix::RY, 'angle' => (float) $_POST['y-rotation']) );
    $_SESSION['from'] = $M->transformVertex($_SESSION['from']);
}
if (isset($_POST['z-rotation'])) {
    $M = new Matrix( array( 'preset' => Matrix::RZ, 'angle' => (float) $_POST['z-rotation']) );
    $_SESSION['from'] = $M->transformVertex($_SESSION['from']);
}


$to = new Vertex( array( 'x' => 1, 'y' => 1, 'z' => -1, 'color' => $color ) );
$viewMatrix = new Matrix( array( 'preset' => Matrix::CAMERATOWORLD , 'from' => $_SESSION['from'], 'to' => $to));

$projectedCorners = [];
foreach ($corners as &$corner) 
{
    $corner = $viewMatrix->transformVertex($corner);
    $projectedCorners [] = Vertex::projectPoint3($corner);
}

$image = imagecreatetruecolor(IMAGE_WIDTH, IMAGE_HEIGHT);
$col_poly = imagecolorallocate($image, $color->red, $color->green, $color->blue);
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

header('Content-type: image/png');

imagepng($image);
imagedestroy($image);
