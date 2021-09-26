<?php

namespace Tzsk\Payu\Gateway;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tzsk\Payu\Actions\Actionable;
use Tzsk\Payu\Actions\VerifyPayuMoney;
use Tzsk\Payu\Exceptions\InvalidValueException;

class PayuMoney extends Gateway
{
    public ?string $key;
    public ?string $salt;
    public ?string $auth;
    public ?string $base;
    public string $serviceProvider = 'payu_paisa';

    protected array $processUrls = [
        self::TEST_MODE => 'https://sandboxsecure.%s/_payment',
        self::LIVE_MODE => 'https://secure.%s/_payment',
    ];

    public function __construct(array $config)
    {
        $this->key = data_get($config, 'key');
        $this->salt = data_get($config, 'salt');
        $this->auth = data_get($config, 'auth');
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
        throw_unless($url, InvalidValueException::fromMessage(__('Invalid mode supplied for PayuMoney'), 'mode'));

        return sprintf($url, $this->base);
    }

    public function verifier(): Actionable
    {
        return new VerifyPayuMoney();
    }

    public function auth(): ?string
    {
        return $this->auth;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'salt' => $this->salt,
            'auth' => $this->auth,
            'endpoint' => $this->endpoint(),
            'service_provider' => $this->serviceProvider,
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
                'auth' => 'required|string',
                'endpoint' => 'required|url',
                'service_provider' => 'required|string',
            ])->validate();
        } catch (ValidationException $e) {
            throw InvalidValueException::fromValidationException($e);
        }
    }

    public function fields(): array
    {
        return collect($this->toArray())
            ->except(['auth', 'endpoint', 'salt'])
            ->all();
    }

    public static function __set_state(array $config)
    {
        return new self($config);
    }
}
