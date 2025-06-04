<?php

namespace App\Exceptions;

use Exception;

class UnableToDeleteException extends Exception
{
    public function __construct($message = "Seller not found", $code = 0)
    {
        parent::__construct($message, $code);
    }
}
