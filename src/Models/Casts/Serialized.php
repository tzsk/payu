<?php

namespace Tzsk\Payu\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Serialized implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return unserialize($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return serialize($value);
    }
}
