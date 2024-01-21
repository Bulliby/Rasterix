<?php

session_start();

require_once '../vendor/autoload.php';

use Waxer\Rasterix\Color;
use Waxer\Rasterix\Matrices\Matrix;
use Waxer\Rasterix\Vertex;
use Waxer\Rasterix\Vector;
use Waxer\Rasterix\Enums\MatrixType;
use Waxer\Rasterix\Image;

$color = new Color(['red' => 255, 'green' => 0, 'blue' => 0]);
$centerColor = new Color(['red' => 255, 'green' => 255, 'blue' => 255]);

const IMAGE_WIDTH = 890;
const IMAGE_HEIGHT = 890;
const CANVAS_WIDTH = 0.5;
const CANVAS_HEIGHT = 0.5; 

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

if (isset($_COOKIE['positions'])) {
    $cookieParsed = json_decode($_COOKIE['positions'], 'array');
    $imageData = new Image(
        $cookieParsed['x-translation'],
        $cookieParsed['y-translation'],
        $cookieParsed['x-rotation'],
        $cookieParsed['y-rotation'],
        $cookieParsed['z-rotation'],
        $cookieParsed['scale'],
    );
    $imageData->imageWidth = $cookieParsed['imageWidth'];
    $imageData->imageHeight = $cookieParsed['imageHeight'];
}
else {
    $imageData = new Image(-4, -4, 1.0, 2.3, 1.2, 221);
}

if (isset($_POST['x-translation']))
    $imageData->xTranslation = $_POST['x-translation'];
if (isset($_POST['y-translation']))
    $imageData->yTranslation = $_POST['y-translation'];
if (isset($_POST['x-rotation']))
    $imageData->xRotation = $_POST['x-rotation'];
if (isset($_POST['y-rotation']))
    $imageData->yRotation = $_POST['y-rotation'];
if (isset($_POST['z-rotation']))
    $imageData->zRotation = $_POST['z-rotation'];
if (isset($_POST['scale']))
    $imageData->scale = $_POST['scale'];


if (!empty($_POST['init'])) {
    $screen_size = json_decode((string) $_POST['init'], true, 2);

    if (!$screen_size) {
        echo "Bad request" . PHP_EOL;
        exit(400);
    }

    $imageData->imageWidth = $screen_size['x'];
    $imageData->imageHeight = $screen_size['y'];

    header('Content-Type: application/json');

    echo json_encode([
        'x-translation' => $imageData->xTranslation,
        'y-translation' => $imageData->yTranslation,
        'x-rotation' => $imageData->xRotation,
        'y-rotation' => $imageData->yRotation,
        'z-rotation' => $imageData->zRotation,
        'scale' => $imageData->scale,
    ]);

    setcookie('positions', json_encode($imageData->toArray()), 0, "", "", false /** secure no https in dev **/, true);
    die();
}

setcookie('positions', json_encode($imageData->toArray()), 0, "", "", false /** secure no https in dev **/, true);

$S = new Matrix(MatrixType::Scale, $imageData->scale);
$vtx = new Vertex(['x' => (float) $imageData->xTranslation, 'y' => (float) $imageData->yTranslation, 'z' => -890]);

if (!$imageData->imageWidth || !$imageData->imageHeight) {
    die();
}

$vtc = new Vector( array( 'dest' => $vtx ) );
$T = new Matrix(MatrixType::Translation, $vtc);
$RX = new Matrix(MatrixType::RX, (float) $imageData->xRotation);
$RY = new Matrix(MatrixType::RY, (float) $imageData->yRotation);
$RZ = new Matrix(MatrixType::RZ, (float) $imageData->zRotation);

$vtc = new Vector( array( 'dest' => $center ) );
$worldToCenter = new Matrix(MatrixType::Translation, $vtc->opposite());
$centerToWorld = new Matrix(MatrixType::Inverse, $worldToCenter);
$projectedCorners = [];

foreach ($corners as &$corner) 
{
    $corner = $worldToCenter->transformVertex($corner); 
    $corner = $RX->multMatrix($RY)->multMatrix($RZ)->transformVertex($corner);
    $corner = $centerToWorld->transformVertex($corner); 
    $corner = $T->multMatrix($S)->transformVertex($corner);
    $projectedCorners [] = projectPoint($corner, $imageData);
}

$image = imagecreatetruecolor($imageData->imageWidth, $imageData->imageHeight);
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

function projectPoint(Vertex $corner, Image $imageData): Vertex
{
    $x_proj = $corner->getX() / - $corner->getZ();
    $y_proj = $corner->getY() / - $corner->getZ();

    //Here 2 is for obtain [0,890] interval and no [-445, 445]
    $x_NDC = ($x_proj + CANVAS_WIDTH / 2) / CANVAS_WIDTH;
    $y_NDC = ($y_proj + CANVAS_HEIGHT / 2) / CANVAS_HEIGHT;
    $x_rast = floor($x_NDC * $imageData->imageWidth);
    $y_rast = floor((1 - $y_NDC) * $imageData->imageHeight);

    return new Vertex( array( 'x' => $x_rast, 'y' => $y_rast, 'color' => $corner->getColor() ) );
}
