<?php

namespace Tzsk\Payu\Concerns;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tzsk\Payu\Contracts\HasFormParams;
use Tzsk\Payu\Exceptions\InvalidValueException;

class Attributes implements HasFormParams
{
    public ?string $udf1 = null;
    public ?string $udf2 = null;
    public ?string $udf3 = null;
    public ?string $udf4 = null;
    public ?string $udf5 = null;
    public ?string $udf6 = null;
    public ?string $udf7 = null;
    public ?string $udf8 = null;
    public ?string $udf9 = null;
    public ?string $udf10 = null;

    public static function make()
    {
        return new self();
    }

    public function udf1(string $udf1): self
    {
        $this->udf1 = $udf1;

        return $this;
    }

    public function udf2(string $udf2): self
    {
        $this->udf2 = $udf2;

        return $this;
    }

    public function udf3(string $udf3): self
    {
        $this->udf3 = $udf3;

        return $this;
    }

    public function udf4(string $udf4): self
    {
        $this->udf4 = $udf4;

        return $this;
    }

    public function udf5(string $udf5): self
    {
        $this->udf5 = $udf5;

        return $this;
    }

    public function udf6(string $udf6): self
    {
        $this->udf6 = $udf6;

        return $this;
    }

    public function udf7(string $udf7): self
    {
        $this->udf7 = $udf7;

        return $this;
    }

    public function udf8(string $udf8): self
    {
        $this->udf8 = $udf8;

        return $this;
    }

    public function udf9(string $udf9): self
    {
        $this->udf9 = $udf9;

        return $this;
    }

    public function udf10(string $udf10): self
    {
        $this->udf10 = $udf10;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'udf1' => $this->udf1,
            'udf2' => $this->udf2,
            'udf3' => $this->udf3,
            'udf4' => $this->udf4,
            'udf5' => $this->udf5,
            'udf6' => $this->udf6,
            'udf7' => $this->udf7,
            'udf8' => $this->udf8,
            'udf9' => $this->udf9,
            'udf10' => $this->udf10,
        ];
    }

    /**
     * @throws InvalidValueException
     */
    public function validate(): array
    {
        try {
            return Validator::make($this->toArray(), [
                'udf1' => 'nullable|string',
                'udf2' => 'nullable|string',
                'udf3' => 'nullable|string',
                'udf4' => 'nullable|string',
                'udf5' => 'nullable|string',
                'udf6' => 'nullable|string',
                'udf7' => 'nullable|string',
                'udf8' => 'nullable|string',
                'udf9' => 'nullable|string',
                'udf10' => 'nullable|string',
            ])->validate();
        } catch (ValidationException $e) {
            throw InvalidValueException::fromValidationException($e);
        }
    }

    public function fields(): array
    {
        return array_filter($this->toArray());
    }
}
