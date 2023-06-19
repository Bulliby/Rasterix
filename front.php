<?php

use Waxer\Rasterix\Color;
use Waxer\Rasterix\Matrix;
use Waxer\Rasterix\Vector;
use Waxer\Rasterix\Vertex;

require_once '/srv/http/vendor/autoload.php';

$color = new Color(['red' => 255, 'green' => 0, 'blue' => 0]);

session_start();
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

if (!isset($_SESSION['corners'])) {
    $_SESSION['corners'] = $corners;
}

$vtx = new Vertex(['x' => 850, 'y' => -150, 'z' => 1]);
$vtc = new Vector(['dest' => $vtx]);
$T = new Matrix( array( 'preset' => Matrix::TRANSLATION, 'vtc' => $vtc) );
$RX = new Matrix( array( 'preset' => Matrix::RX, 'angle' => 1.8) );
$RY = new Matrix( array( 'preset' => Matrix::RY, 'angle' => 2) );
$CTW = new Matrix( array( 'preset' => Matrix::CAMERA_TO_WORLD) );
$WTC = new Matrix( array( 'preset' => Matrix::INVERSE, 'src' => $CTW) );

$scale = $_POST['scale'] ?? null;
$xTranslation = $_POST['x-translation'] ?? null;
$yTranslation = $_POST['y-translation'] ?? null;

$M = new Matrix( array( 'preset' => Matrix::IDENTITY));

if ($scale) {
    $M = new Matrix( array( 'preset' => Matrix::SCALE, 'scale' => $scale) );
}

if ($xTranslation) {
    $vtx = new Vertex(['x' => $xTranslation, 'y' => 0, 'z' => 1]);
    $vtc = new Vector(['dest' => $vtx]);
    $M = new Matrix( array( 'preset' => Matrix::TRANSLATION, 'vtc' => $vtc) );
}

if ($yTranslation) {
    $vtx = new Vertex(['x' => 0, 'y' => $yTranslation, 'z' => 1]);
    $vtc = new Vector(['dest' => $vtx]);
    $M = new Matrix( array( 'preset' => Matrix::TRANSLATION, 'vtc' => $vtc) );
}

foreach ($_SESSION['corners'] as &$corner) 
{
    $x_proj = $corner->getX() / - $corner->getZ();
    $y_proj = $corner->getY() / - $corner->getZ();
    $x_proj_remap = (1 + $x_proj) / IMAGE_WIDTH;
    $y_proj_remap = (1 + $y_proj) / IMAGE_HEIGHT;
    $corner = new Vertex( array( 'x' => $x_proj_remap * IMAGE_WIDTH, 'y' => $y_proj_remap * IMAGE_HEIGHT, 'color' => $color ) );
    $corner = $CTW->mult($M)->multiplication($corner);
}

$corners = $_SESSION['corners'];

$image = imagecreatetruecolor(IMAGE_WIDTH, IMAGE_HEIGHT);
$col_poly = imagecolorallocate($image, $color->red, $color->green, $color->blue);

imagepolygon($image, [
    $corners[5]->getX(), $corners[5]->getY(),
    $corners[1]->getX(), $corners[1]->getY(),
    $corners[3]->getX(), $corners[3]->getY(),
    $corners[7]->getX(), $corners[7]->getY(),
], 4, $col_poly);

imagepolygon($image, [
    $corners[5]->getX(), $corners[5]->getY(),
    $corners[4]->getX(), $corners[4]->getY(),
    $corners[6]->getX(), $corners[6]->getY(),
    $corners[7]->getX(), $corners[7]->getY(),
], 4, $col_poly);

imagepolygon($image, [
    $corners[5]->getX(), $corners[5]->getY(),
    $corners[4]->getX(), $corners[4]->getY(),
    $corners[6]->getX(), $corners[6]->getY(),
    $corners[7]->getX(), $corners[7]->getY(),
], 4, $col_poly);

imagepolygon($image, [
    $corners[4]->getX(), $corners[4]->getY(),
    $corners[0]->getX(), $corners[0]->getY(),
    $corners[2]->getX(), $corners[2]->getY(),
    $corners[6]->getX(), $corners[6]->getY(),
], 4, $col_poly);

imagepolygon($image, [
    $corners[0]->getX(), $corners[0]->getY(),
    $corners[1]->getX(), $corners[1]->getY(),
    $corners[3]->getX(), $corners[3]->getY(),
    $corners[2]->getX(), $corners[2]->getY(),
], 4, $col_poly);


header('Content-type: image/png');

imagepng($image);
imagedestroy($image);
