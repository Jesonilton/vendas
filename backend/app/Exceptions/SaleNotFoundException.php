<?php

namespace App\Exceptions;

use Exception;

class SaleNotFoundException extends Exception
{
    public function __construct($message = "Sale not found", $code = 0)
    {
        parent::__construct($message, $code);
    }
}
