<?php

namespace App\Exceptions;


class TagsException extends \Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }

}
