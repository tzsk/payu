<?php
namespace Tzsk\Payu;

use Illuminate\Support\Facades\Session;
use Tzsk\Payu\Helpers\Config;
use Tzsk\Payu\Helpers\Redirector;
use Tzsk\Payu\Helpers\Storage;
use Tzsk\Payu\Model\PayuPayment;

class PayuGateway
{
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
     * @return $this
     */
    public function with($model)
    {
        $this->model = $model;

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

        $session = [
            'data' => $data,
            'status_url' => $redirector->getUrl(),
            'model' => $this->model ? [
                'id' => $this->model->id,
                'class' => get_class($this->model)
            ] : null
        ];
        session()->put('tzsk_payu_data', $session);

        return redirect()->to('tzsk/payment');
    }

    /**
     * Receive Payment and Return Payment Model.
     *
     * @return PayuPayment
     */
    public function capture()
    {
        $storage = new Storage();
        $config = new Config();

        if ($config->getDriver() == 'database') {
            return PayuPayment::find($storage->getPayment());
        }

        return new PayuPayment($storage->getPayment());
    }

    /**
     * Get Status of a given Transaction.
     *
     * @param $txnid string
     * @return object
     */
    public function verify($txnid)
    {
        $txnid = is_array($txnid) ? $txnid : [$txnid];
        $verification = new PaymentVerification($txnid);

        return $verification->request();
    }
}
