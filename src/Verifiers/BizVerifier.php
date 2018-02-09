<?php

namespace Tzsk\Payu\Verifiers;

use Tzsk\Payu\Helpers\Config;
use Tzsk\Payu\Model\PayuPayment;
use Tzsk\Payu\Helpers\Processor;
use Illuminate\Http\Request;

class BizVerifier extends AbstractVerifier
{
    /**
     * Command to Pass to the PayuService.
     *
     * @var string
     */
    protected $command = 'verify_payment';

    /**
     * Request for Verification Status.
     *
     * @return $this
     */
    public function verify()
    {
        try {
            $response = $this->client->request('POST', $this->url(), [
                'form_params' => $this->fields()
            ]);
    
            return $this->makeResponse(json_decode($response->getBody()));
        } catch (\Exception $e) {
            return (object) ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * @return string
     */
    protected function url()
    {
        return "https://{$this->prefix()}.payu.in/merchant/postservice?form=2";
    }

    /**
     * @return array
     */
    protected function fields()
    {
        $txn_string = implode("|", $this->txnIds);
        $hash_str = $this->config->getKey().'|'.$this->command.'|'.$txn_string.'|'.$this->config->getSalt();

        return [
            'key' => $this->config->getKey(),
            'hash' => strtolower(hash('sha512', $hash_str)),
            'var1' => $txn_string,
            'command' => $this->command
        ];
    }

    /**
     * @return string
     */
    protected function prefix()
    {
        $env = $this->config->getEnv();
        if ($env != 'test') {
            $env = 'info';
        }

        return $env;
    }

    /**
     * @param object $data
     * @return array
     */
    protected function makeResponse($data)
    {
        if ($data->status < 1) {
            throw new \Exception($data->msg);
        }
        
        $response = ['status' => true, 'data' => []];

        foreach ($this->txnIds as $id) {
            $response['data'][$id] = $this->getInstance($data, $id);
        }

        return (object) $response;
    }

    /**
     * @param object $data
     * @param string $id
     * @return PayuPayment
     */
    protected function getInstance($data, $id)
    {
        $request = new Request((array) $data->transaction_details->{$id});
        $attributes = (new Processor($request))->process();

        if ($this->config->getDriver() == 'database') {
            return PayuPayment::find($attributes);
        }

        return new PayuPayment($attributes);
    }
}
