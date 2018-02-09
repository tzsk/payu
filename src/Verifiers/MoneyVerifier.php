<?php

namespace Tzsk\Payu\Verifiers;

use Illuminate\Http\Request;
use Tzsk\Payu\Helpers\Processor;
use Tzsk\Payu\Model\PayuPayment;

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
        // https://test.payumoney.com/payment/payment/chkMerchantTxnStatus
        $src = "https://{$this->prefix()}.payumoney.com/payment/payment/chkMerchantTxnStatus?";

        return $src . http_build_query($this->fields());
    }

    /**
     * @return array
     */
    protected function fields()
    {
        return [
            'merchantKey' => $this->config->getKey(),
            'merchantTransactionIds' => implode("|", $this->txnIds)
        ];
    }

    /**
     * @return string
     */
    protected function prefix()
    {
        $env = $this->config->getEnv();

        return ($env == 'test') ? 'test' : 'www';
    }

    /**
     * @param object $data
     * @return array
     */
    protected function makeResponse($data)
    {
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
