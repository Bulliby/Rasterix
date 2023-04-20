<?php

use Waxer\Rasterix\Color;
use Waxer\Rasterix\Matrix;
use Waxer\Rasterix\Vector;
use Waxer\Rasterix\Vertex;

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

$vtx = new Vertex(['x' => 100, 'y' => -100, 'z' => 0]);
$vtc = new Vector(['dest' => $vtx]);
$S = new Matrix( array( 'preset' => Matrix::SCALE, 'scale' => 500) );
$T = new Matrix( array( 'preset' => Matrix::TRANSLATION, 'vtc' => $vtc) );
$RX = new Matrix( array( 'preset' => Matrix::RX, 'angle' => 1) );
$RY = new Matrix( array( 'preset' => Matrix::RY, 'angle' => 1) );

foreach ($corners as &$corner) 
{
    $corner = $S->multiplication($corner);
    $x_proj = $corner->getX() / - $corner->getZ();
    $y_proj = $corner->getY() / - $corner->getZ();
    $x_proj_remap = (1 + $x_proj) / IMAGE_WIDTH;
    $y_proj_remap = (1 + $y_proj) / IMAGE_HEIGHT;
    $corner = new Vertex( array( 'x' => $x_proj_remap * IMAGE_WIDTH, 'y' => $y_proj_remap * IMAGE_HEIGHT, 'color' => $color ) );
    $corner = $T->mult($RX)->mult($RY)->mult($S)->multiplication($corner);
}

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
