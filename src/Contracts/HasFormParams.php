<?php

namespace Tzsk\Payu\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface HasFormParams extends Arrayable
{
    public function validate(): array;

    public function fields(): array;
}
