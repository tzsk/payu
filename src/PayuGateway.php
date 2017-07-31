<?php
namespace Tzsk\Payu;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Tzsk\Payu\Helpers\Redirector;
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
        Cache::put('tzsk_data', $data, 5);
        Cache::put('tzsk_status_url', $redirector->getUrl(), 5);
        Cache::put('tzsk_model', $this->model, 15);

        return redirect()->to('tzsk/payment');
    }

    /**
     * Receive Payment and Return Payment Model.
     *
     * @return PayuPayment
     */
    public function capture()
    {
        $pay = Cache::get('tzsk_payment');

        if (config('payu.driver') == 'database') {
            return PayuPayment::find($pay);
        }

        return new PayuPayment($pay);
    }

    /**
     * Get Status of a given Transaction.
     *
     * @param $txn_id
     * @return object
     */
    public function verify($txnid)
    {
        $txnid = is_array($txnid) ? $txnid : [$txnid];
        $verification = new PaymentVerification($txnid);

        return $verification->request();
    }


}
