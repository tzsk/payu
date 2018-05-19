<?php

namespace Tzsk\Payu\Verifiers;

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
        $txn_string = implode('|', $this->txnIds);
        $hash_str = $this->config->getKey() . '|' . $this->command . '|' . $txn_string . '|' . $this->config->getSalt();

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

        return ($env == 'test') ? $env : 'info';
    }

    /**
     * @param object $data
     * @return array
     */
    protected function makeResponse($data)
    {
        if ($data->status < 1) {
            return (object) ['status' => false, 'data' => [], 'message' => $data->msg];
        }

        $response = ['status' => true, 'data' => [], 'message' => ''];

        foreach ($this->txnIds as $id) {
            if (! empty($data->transaction_details->{$id})) {
                $response['data'][$id] = $this->getInstance($data->transaction_details->{$id});
            }
        }

        return (object) $response;
    }
}
