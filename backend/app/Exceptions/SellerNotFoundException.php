<?php

namespace App\Exceptions;

use Exception;

class SellerNotFoundException extends Exception
{
    public function __construct($message = "Seller not found", $code = 0)
    {
        parent::__construct($message, $code);
    }
}
