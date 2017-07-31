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

        $this->status_url = empty($data['status_url']) ? null : $data['status_url'];
        $this->model = empty($data['model']) ? null : $data['model'];
        $this->payment = empty($data['payment']) ? null : $data['payment'];
        $this->data = empty($data['data']) ? [] : $data['data'];
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
}
