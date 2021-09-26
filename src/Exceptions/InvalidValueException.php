<?php

namespace Tzsk\Payu\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;

class InvalidValueException extends Exception
{
    public ?ValidationException $validationException;

    public function __construct(string $message = 'Invalid Value', ?ValidationException $ex = null)
    {
        parent::__construct($message, 400, $ex);
        $this->validationException = $ex;
    }

    public static function fromValidationException(ValidationException $ex)
    {
        return new self($ex->validator->errors()->first(), $ex);
    }

    public static function fromMessage(string $message, string $field)
    {
        return new self($message, ValidationException::withMessages([$field => $message]));
    }
}
