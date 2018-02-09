<?php

namespace Tzsk\Payu\Verifiers;

use GuzzleHttp\Client;
use Tzsk\Payu\Helpers\Config;

abstract class AbstractVerifier
{
    /**
     * @var array
     */
    protected $txnIds = [];

    /**
     * @var Client
     */
    protected $config;

    /**
     * @param array $transaction_ids
     * @param string $account
     */
    public function __construct($transaction_ids, $account = null)
    {
        $this->txnIds = $transaction_ids;
        $this->config = new Config($account);
        $this->client = new Client();
    }

    /**
     * @return object
     */
    abstract public function verify();
}