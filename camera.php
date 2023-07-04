<?php

use Waxer\Rasterix\Color;
use Waxer\Rasterix\Matrix;
use Waxer\Rasterix\Vertex;

require_once '/srv/http/vendor/autoload.php';

$color = new Color(['red' => 255, 'green' => 0, 'blue' => 0]);

const IMAGE_WIDTH = 1600;
const IMAGE_HEIGHT = 1300;

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
    $_SESSION['x-translation'] = 11;
    $_SESSION['y-translation'] = 9;
    $_SESSION['z-translation'] = -10;
}

if ($_POST['x-translation']) {
    $_SESSION['x-translation'] += (int) $_POST['x-translation'];
}
if ($_POST['y-translation']) {
    $_SESSION['y-translation'] += (int) $_POST['y-translation'];
}
if ($_POST['z-translation']) {
    $_SESSION['z-translation'] += (int) $_POST['z-translation'];
}

$to = new Vertex( array( 'x' => $corner1->getX(), 'y' => $corner1->getY(), 'z' => $corner1->getZ(), 'color' => $color ) );
$from = new Vertex(['x' => $_SESSION['x-translation'], 'y' => $_SESSION['y-translation'], 'z' => $_SESSION['z-translation']]);
$viewMatrix = new Matrix( array( 'preset' => Matrix::CAMERATOWORLD , 'from' => $from, 'to' => $to));

/* $to = new Vertex( array( 'x' => 0, 'y' => 0, 'z' => 0, 'color' => $color ) ); */
/* $from = new Vertex(['x' => 1, 'y' => 1, 'z' => 1]); */
/* $viewMatrix = new Matrix( array( 'preset' => Matrix::CAMERATOWORLD , 'from' => $from, 'to' => $to)); */

$projectedCorners = [];
foreach ($corners as &$corner) 
{
    $corner = $viewMatrix->transformVertex($corner);
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
