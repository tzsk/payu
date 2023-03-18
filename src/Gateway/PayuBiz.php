<?php

namespace Tzsk\Payu\Gateway;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tzsk\Payu\Actions\Actionable;
use Tzsk\Payu\Actions\VerifyPayuBiz;
use Tzsk\Payu\Exceptions\InvalidValueException;

class PayuBiz extends Gateway
{
    public ?string $key;

    public ?string $salt;

    public ?string $base;

    protected array $processUrls = [
        self::TEST_MODE => 'https://test.%s/_payment',
        self::LIVE_MODE => 'https://secure.%s/_payment',
    ];

    public function __construct(array $config)
    {
        $this->key = data_get($config, 'key');
        $this->salt = data_get($config, 'salt');
        $this->base = data_get($config, 'base', 'payu.in');
        $this->mode = data_get($config, 'mode', self::TEST_MODE);
    }

    public function salt(): ?string
    {
        return $this->salt;
    }

    public function endpoint(): ?string
    {
        $url = data_get($this->processUrls, $this->mode);
        throw_unless($url, InvalidValueException::fromMessage(__('Invalid mode supplied for PayuBiz'), 'mode'));

        return sprintf($url, $this->base);
    }

    public function auth(): ?string
    {
        return null;
    }

    public function verifier(): Actionable
    {
        return new VerifyPayuBiz();
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'salt' => $this->salt,
            'endpoint' => $this->endpoint(),
        ];
    }

    /**
     * @throws InvalidValueException
     */
    public function validate(): array
    {
        try {
            return Validator::make($this->toArray(), [
                'key' => 'required|string',
                'salt' => 'required|string',
                'endpoint' => 'required|url',
            ])->validate();
        } catch (ValidationException $e) {
            throw InvalidValueException::fromValidationException($e);
        }
    }

    public function fields(): array
    {
        return collect($this->toArray())
            ->except(['endpoint', 'salt'])
            ->all();
    }

    public static function __set_state(array $config)
    {
        return new self($config);
    }
}
