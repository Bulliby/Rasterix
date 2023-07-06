<?php

/**
 * Here i list the vertex to draw my test cube
 */

// Eight corners
$corners = [
    [ 1, -1, -5], //Rouge - 1
    [ 1, -1, -3], //Vert - 2
    [ 1,  1, -5], //Bleu - 3
    [ 1,  1, -3], //Noir - 4
    [-1, -1, -5], //Jaune - ...
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
