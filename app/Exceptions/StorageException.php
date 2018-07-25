<?php

namespace App\Exceptions;


class StorageException extends \Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }

}
