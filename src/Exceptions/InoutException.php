<?php

namespace Mchervenkov\Inout\Exceptions;

use Exception;

class InoutException extends Exception
{
    protected $errors;

    /**
     * __construct
     *
     * @param  string $message
     * @param  int $code
     * @param  array $errors Errors
     * @return void
     */
    public function __construct($message, $code = 0, $errors = null)
    {
        parent::__construct($message, $code);

        $this->errors = $errors;
    }

    /**
     * getErrors
     *
     * @return array
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }
}
