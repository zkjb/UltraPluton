<?php

declare(strict_types=1);

namespace Ultrapluton\DatabaseConnection\Exception;

class DatabaseConnectionException extends \PDOException
{
    protected $message;
    protected $code;

    public function __constructor($message = null, $code = null)
    {
        $this->message = $message;
        $this->code = $code;
    }


}