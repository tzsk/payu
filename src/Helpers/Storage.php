<?php

namespace Tzsk\Payu\Helpers;

use Tzsk\Payu\Model\PayuPayment;
use Illuminate\Support\Facades\Session;

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
     * @var string
     */
    protected $account;

    /**
     * Storage constructor.
     */
    public function __construct()
    {
        $data = Session::get('tzsk_payu_data');

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }

        $this->boot();
    }

    /**
     * @return void
     */
    public function boot()
    {
        if (! $this->account) {
            $this->account = config('payu.default');
        }
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
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }
}
