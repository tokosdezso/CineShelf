<?php

namespace App\Exceptions;

use Exception;

class ApiResponseException extends Exception
{
    protected $statusCode;
    protected $data;

    public function __construct($message = "", $statusCode = 400, $data = null)
    {
        parent::__construct($message, $statusCode);
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getData()
    {
        return $this->data;
    }
}
