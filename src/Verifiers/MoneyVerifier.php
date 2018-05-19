<?php

namespace Tzsk\Payu\Verifiers;

class MoneyVerifier extends AbstractVerifier
{
    /**
     * @return object
     */
    public function verify()
    {
        try {
            $response = $this->client->request('POST', $this->url(), [
                'headers' => [
                    'Authorization' => $this->config->getAuth()
                ],
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
        $src = "https://www.payumoney.com/{$this->prefix()}payment/op/getPaymentResponse?";

        return $src . http_build_query($this->fields());
    }

    /**
     * @return array
     */
    protected function fields()
    {
        return [
            'merchantKey' => $this->config->getKey(),
            'merchantTransactionIds' => implode('|', $this->txnIds)
        ];
    }

    /**
     * @return string
     */
    protected function prefix()
    {
        $env = $this->config->getEnv();

        return ($env == 'test') ? 'sandbox/' : '';
    }

    /**
     * @param object $data
     * @return array
     */
    protected function makeResponse($data)
    {
        if (! empty($data->errorCode)) {
            return (object) ['status' => false, 'data' => [], 'message' => $data->message];
        }
        $response = ['status' => true, 'data' => [], 'message' => ''];
        $collection = collect($data->result)->pluck('postBackParam', 'merchantTransactionId');

        foreach ($this->txnIds as $id) {
            if (! empty($collection[$id])) {
                $response['data'][$id] = $this->getInstance($collection[$id]);
            }
        }

        return (object) $response;
    }
}
