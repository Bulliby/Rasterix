<?php

namespace Waxer\Rasterix;

class InvalidArgumentsException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Some parameters are missing';
}
