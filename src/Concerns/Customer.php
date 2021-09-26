<?php

namespace Tzsk\Payu\Concerns;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tzsk\Payu\Contracts\HasFormParams;
use Tzsk\Payu\Exceptions\InvalidValueException;

class Customer implements HasFormParams
{
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $addressOne = null;
    public ?string $addressTwo = null;
    public ?string $city = null;
    public ?string $state = null;
    public ?string $country = null;
    public ?string $zipCode = null;

    public static function make(): self
    {
        return new self();
    }

    public function firstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function lastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function phone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function addressOne(string $addressOne): self
    {
        $this->addressOne = $addressOne;

        return $this;
    }

    public function addressTwo(string $addressTwo): self
    {
        $this->addressTwo = $addressTwo;

        return $this;
    }

    public function city(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function state(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function country(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function zipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'firstname' => $this->firstName,
            'lastname' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'address1' => $this->addressOne,
            'address2' => $this->addressTwo,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'zipcode' => $this->zipCode,
        ];
    }

    /**
     * @throws InvalidValueException
     */
    public function validate(): array
    {
        try {
            return Validator::make($this->toArray(), [
                'firstname' => 'required|string',
                'lastname' => 'nullable|string',
                'email' => 'required|email',
                'phone' => 'nullable|string',
                'address1' => 'nullable|string',
                'address2' => 'nullable|string',
                'city' => 'nullable|string',
                'state' => 'nullable|string',
                'country' => 'nullable|string',
                'zipcode' => 'nullable|string',
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
