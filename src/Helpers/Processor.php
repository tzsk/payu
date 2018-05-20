<?php

namespace Tzsk\Payu\Helpers;

use Illuminate\Http\Request;
use Tzsk\Payu\Model\PayuPayment;

class Processor
{
    /**
     * Payment statuses.
     */
    const STATUS_COMPLETED = 'Completed';
    const STATUS_PENDING = 'Pending';
    const STATUS_FAILED = 'Failed';

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * ProcessPayment constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->storage = new Storage();
        $this->config = new Config($this->storage->getAccount());
    }

    /**
     * Get the status of the Payment.
     *
     * @return string
     */
    public function getStatus()
    {
        switch (strtolower($this->request->status)) {
            case 'success':
                return self::STATUS_COMPLETED;

            case 'pending':
                return self::STATUS_PENDING;

            case 'failure':
                return self::STATUS_FAILED;

            default:
                return self::STATUS_FAILED;
        }
    }

    /**
     * @return mixed
     */
    public function process()
    {
        $model = $this->storage->getModel();
        $attributes = $this->getAttributes();

        if ($this->config->getDriver() == 'database') {
            $attributes = $this->linkToDatabase($attributes);
        }

        return $attributes;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $attributes = $this->request->only([
            'txnid', 'mihpayid', 'firstname', 'email', 'phone', 'amount', 'discount',
            'net_amount_debit', 'data', 'status', 'unmappedstatus', 'mode', 'bank_ref_num',
            'bankcode', 'cardnum', 'name_on_card', 'issuing_bank', 'card_type'
        ]);
        $attributes['data'] = json_encode($this->request->all());
        $attributes['status'] = $this->getStatus();
        $attributes['account'] = $this->storage->getAccount();

        $modelArray = $this->storage->getModel();
        if (! empty($modelArray)) {
            $attributes['payable_id'] = $modelArray['id'];
            $attributes['payable_type'] = $modelArray['class'];
        }

        return $attributes;
    }

    /**
     * @return integer
     */
    protected function linkToDatabase($attributes)
    {
        $instance = PayuPayment::firstOrNew(['txnid' => $attributes['txnid']]);
        $instance->fill(array_filter($attributes))->save();

        if (! empty($model)) {
            $instance->fill([
                'payable_id' => $model['id'],
                'payable_type' => $model['class']
            ])->save();
        }

        return $instance->id;
    }

    /**
     * @return null|string
     */
    public function getTransactionId()
    {
        return $this->request->has('mihpayid') ? (string) $this->request->mihpayid : null;
    }

    /**
     * @return null|string
     */
    protected function getHash()
    {
        return $this->request->has('hash') ? (string) $this->request->hash : null;
    }
}
