<?php

/**
 * Make move a model on his axis center
 * Define a coordinate system in the centre of model
 * Apply world to center Matrix
 * Apply rotation and other transformation
 * Apply the Center to World matrix
 * Apply the world to camera matrix
 * Project
 */

use Waxer\Rasterix\Color;
use Waxer\Rasterix\Matrix;
use Waxer\Rasterix\Vertex;
use Waxer\Rasterix\Vector;

require_once '/srv/http/vendor/autoload.php';

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

$worldToCenter = new Matrix(['preset' => Matrix::CENTER, 'center' => $center]);
$centerToWorld = new Matrix(['preset' => Matrix::INVERSE, 'matrix' => $worldToCenter]);

$from = new Vertex(['x' => 0, 'y' => 0, 'z' => 0]);
$to = new Vertex( array( 'x' => 445, 'y' => 445, 'z' => -445, 'color' => $color ) );
$cameraToWorld = new Matrix( array( 'preset' => Matrix::CAMERATOWORLD , 'from' => $from, 'to' => $to));
$worldToCamera = new Matrix(['preset' => Matrix::INVERSE, 'matrix' => $cameraToWorld]);
$projection = new Matrix(['preset' => Matrix::PROJECTION, 'fov' => 60, 'ratio' => 1, 'near' => 1.0, 'far' => -50.0]);

$S  = new Matrix( array( 'preset' => Matrix::SCALE, 'scale' => 40.0 ) );
$vtx = new Vertex( array( 'x' => 445, 'y' => 445, 'z' => 1 ) );
$vtc = new Vector( array( 'dest' => $vtx ) );
$T  = new Matrix( array( 'preset' => Matrix::TRANSLATION, 'vtc' => $vtc ) );
$RY = new Matrix( array( 'preset' => Matrix::RY, 'angle' => 0.8) );

foreach ($corners as &$corner) 
{
    $corner = $worldToCenter->multMatrix($RY)->transformVertex($corner); 
    $corner = $centerToWorld->transformVertex($corner); 
    $corner = $T->multMatrix($S)->transformVertex($corner);
    $corner = $worldToCamera->transformVertex($corner);
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
