<?php

namespace App\Exceptions;


class ImagesException extends \Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }

}
