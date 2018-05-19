<?php

namespace Tzsk\Payu;

use Tzsk\Payu\Helpers\Config;
use Tzsk\Payu\Helpers\Storage;
use Tzsk\Payu\Model\PayuPayment;
use Tzsk\Payu\Helpers\Redirector;
use Tzsk\Payu\Verifiers\BizVerifier;
use Tzsk\Payu\Verifiers\MoneyVerifier;
use Tzsk\Payu\Verifiers\AbstractVerifier;

class PayuGateway
{
    /**
     * Account to use
     *
     * @var string
     */
    protected $account;

    /**
     * Model to add;
     *
     * @var null
     */
    protected $model = null;

    /**
     * Pass any model to Add Polymorphic Relation.
     *
     * @param $model
     * @return PayuGateway
     */
    public function with($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param string $account
     * @return PayuGateway
     */
    public function via($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     *
     * @param array $data
     * @param $callback
     * @return \Illuminate\Http\RedirectResponse
     */
    public function make(array $data, $callback)
    {
        $redirector = new Redirector();
        call_user_func($callback, $redirector);

        session()->put('tzsk_payu_data', $this->makeSession($data, $redirector));

        return redirect()->to('tzsk/payment');
    }

    /**
     * @param array $data
     * @param Redirector $redirector
     * @return array
     */
    protected function makeSession($data, Redirector $redirector)
    {
        return [
            'data' => $data,
            'status_url' => $redirector->getUrl(),
            'account' => $this->account,
            'model' => $this->model ? [
                'id' => $this->model->id,
                'class' => get_class($this->model)
            ] : null
        ];
    }

    /**
     * Receive Payment and Return Payment Model.
     *
     * @return PayuPayment
     */
    public function capture()
    {
        $storage = new Storage();
        $config = new Config($storage->getAccount());

        if ($config->getDriver() == 'database') {
            return PayuPayment::find($storage->getPayment());
        }

        return new PayuPayment($storage->getPayment());
    }

    /**
     * Get Status of a given Transaction.
     *
     * @param $transactionId string
     * @return object
     */
    public function verify($transactionId)
    {
        session()->put('tzsk_payu_data', ['account' => $this->account]);

        $transactionId = is_array($transactionId) ?
            $transactionId : explode('|', $transactionId);

        return $this->getVerifier($transactionId)->verify();
    }

    /**
     * @param string $transactionId
     * @return AbstractVerifier
     */
    protected function getVerifier($transactionId)
    {
        $account = $this->account ? $this->account : config('payu.default');
        $config = new Config($account);

        if ($config->isPayuMoney()) {
            return new MoneyVerifier($transactionId, $account);
        }

        return new BizVerifier($transactionId, $account);
    }
}
