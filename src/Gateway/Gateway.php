<?php

namespace Tzsk\Payu\Gateway;

use Tzsk\Payu\Actions\Actionable;
use Tzsk\Payu\Contracts\HasFormParams;

abstract class Gateway implements HasFormParams
{
    const TEST_MODE = 'test';
    const LIVE_MODE = 'live';

    public string $mode;

    abstract public function __construct(array $config);

    abstract public function salt(): ?string;

    abstract public function endpoint(): ?string;

    abstract public function auth(): ?string;

    abstract public function verifier(): Actionable;
}
