<?php

namespace Tzsk\Payu\Verifiers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Tzsk\Payu\Helpers\Config;
use Tzsk\Payu\Helpers\Processor;
use Tzsk\Payu\Model\PayuPayment;

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
     * @param object $data
     * @return PayuPayment
     */
    protected function getInstance($data)
    {
        $request = new Request((array) $data);
        $attributes = (new Processor($request))->process();

        if ($this->config->getDriver() == 'database') {
            return PayuPayment::find($attributes);
        }

        return new PayuPayment($attributes);
    }

    /**
     * @return object
     */
    abstract public function verify();
}
