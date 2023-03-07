<?php

// Eight corners
$corners = [
    [ 1, -1, -5], //Rouge
    [ 1, -1, -3], //Vert
    [ 1,  1, -5], //Bleu
    [ 1,  1, -3], //Noir
    [-1, -1, -5], //Jaune
    [-1, -1, -3], //Orange
    [-1,  1, -5], //Maron
    [-1,  1, -3]  //Rose
];
//Polygon 1 : 6, 2, 8, 4
//Polygon 2:  6, 5, 7, 8
//Polygon 3: 4, 1, 3, 7
//Polygon 4: 1, 2, 3, 4
//Polygon 5: 6, 5 1, 2
//Polygon 6: 8, 7, 3, 4


$image_width = 512;
$image_height = 512;
$x_proj_pix = [];
$y_proj_pix = [];
for ($i = 0; $i < 8; ++$i) {
    // divide the x and y coordinates by the z coordinate to 
    // project the point on the canvas
    $x_proj = $corners[$i][0] / - $corners[$i][2];
    $y_proj = $corners[$i][1] / - $corners[$i][2];
    $x_proj_remap = (1 + $x_proj) / 2;
    $y_proj_remap = (1 + $y_proj) / 2;
    $x_proj_pix[$i] = $x_proj_remap * $image_width;
    $y_proj_pix[$i] = $y_proj_remap * $image_height;
}

$image = imagecreatetruecolor(520, 520);
$col_poly = imagecolorallocate($image, 255, 255, 255);
imagepolygon($image, [
    $x_proj_pix[5], $y_proj_pix[5],
    $x_proj_pix[1], $y_proj_pix[1],
    $x_proj_pix[3], $y_proj_pix[3],
    $x_proj_pix[7], $y_proj_pix[7],
], 4, $col_poly);

$col_poly = imagecolorallocate($image, 255, 255, 255);
imagepolygon($image, [
    $x_proj_pix[5], $y_proj_pix[5],
    $x_proj_pix[4], $y_proj_pix[4],
    $x_proj_pix[6], $y_proj_pix[6],
    $x_proj_pix[7], $y_proj_pix[7],
], 4, $col_poly);

$col_poly = imagecolorallocate($image, 255, 255, 255);
imagepolygon($image, [
    $x_proj_pix[5], $y_proj_pix[5],
    $x_proj_pix[4], $y_proj_pix[4],
    $x_proj_pix[6], $y_proj_pix[6],
    $x_proj_pix[7], $y_proj_pix[7],
], 4, $col_poly);

/* $corners = [ */
/*     [ 1, -1, -5], //Rouge */
/*     [ 1, -1, -3], //Vert */
/*     [ 1,  1, -5], //Bleu */
/*     [ 1,  1, -3], //Noir */
/*     [-1, -1, -5], //Jaune */
/*     [-1, -1, -3], //Orange */
/*     [-1,  1, -5], //Maron */
/*     [-1,  1, -3]  //Rose */
/* ]; */
$col_poly = imagecolorallocate($image, 255, 255, 255);
imagepolygon($image, [
    $x_proj_pix[4], $y_proj_pix[4],
    $x_proj_pix[0], $y_proj_pix[0],
    $x_proj_pix[2], $y_proj_pix[2],
    $x_proj_pix[6], $y_proj_pix[6],
], 4, $col_poly);

$col_poly = imagecolorallocate($image, 255, 255, 255);
imagepolygon($image, [
    $x_proj_pix[0], $y_proj_pix[0],
    $x_proj_pix[1], $y_proj_pix[1],
    $x_proj_pix[3], $y_proj_pix[3],
    $x_proj_pix[2], $y_proj_pix[2],
], 4, $col_poly);

header('Content-type: image/png');

imagepng($image);
imagedestroy($image);
