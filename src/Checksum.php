<?php

namespace Tzsk\Payu;

class Checksum
{
    const REVERSE = true;

    protected string $salt;

    protected array $keys = [
        'key', 'txnid', 'amount', 'productinfo', 'firstname', 'email',
    ];

    public function __construct(string $salt)
    {
        $this->salt = $salt;
    }

    public static function with($salt): self
    {
        return new self($salt);
    }

    public function create(array $fields): string
    {
        return $this->generate($fields);
    }

    public function match(array $fields, string $hash): bool
    {
        return $this->generate($fields, self::REVERSE) == $hash;
    }

    public function generate(array $fields, bool $reverse = false)
    {
        $keys = collect($this->keys)
            ->merge(collect(range(1, 10))->map(fn (int $index) => "udf{$index}"));

        $keys = $reverse ? $keys->merge(['status', 'salt'])->reverse() : $keys->merge(['salt']);

        $attributes = collect($fields)
            ->put('salt', $this->salt)
            ->all();

        return hash('sha512', implode('|', $this->getSequence($attributes, $keys->all())));
    }

    protected function getSequence(array $fields, array $keys): array
    {
        $sequence = [];
        foreach ($keys as $key) {
            $sequence[] = data_get($fields, $key);
        }

        return $sequence;
    }
}
