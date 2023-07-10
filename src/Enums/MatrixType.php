<?php

namespace Waxer\Rasterix\Enums;

enum MatrixType
{
    case Identity;
    case Scale;
    case RX;
    case RY;
    case RZ;
    case Translation;
    case Projection;
    case View;
    case Inverse;
    case Custom;
}
