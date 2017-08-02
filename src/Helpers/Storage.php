<?php
namespace Tzsk\Payu\Helpers;

use Illuminate\Support\Facades\Session;
use Tzsk\Payu\Model\PayuPayment;

class Storage
{
    /**
     * @var string
     */
    protected $status_url;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var PayuPayment
     */
    protected $payment;

    /**
     * Storage constructor.
     */
    public function __construct()
    {
        $data = Session::get('tzsk_payu_data');

        $this->setStatusUrl(@$data['status_url']);
        $this->setModel(@$data['model']);
        $this->setPayment(@$data['payment']);
        $this->setData(@$data['data']);
    }

    /**
     * @return string
     */
    public function getStatusUrl()
    {
        return $this->status_url;
    }

    /**
     * @return PayuPayment
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return PayuPayment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $status_url
     */
    public function setStatusUrl($status_url = null)
    {
        $this->status_url = $status_url;
    }

    /**
     * @param string $model
     */
    public function setModel($model = null)
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     */
    public function setData($data = [])
    {
        $this->data = $data;
    }

    /**
     * @param PayuPayment $payment
     */
    public function setPayment($payment = null)
    {
        $this->payment = $payment;
    }
}
