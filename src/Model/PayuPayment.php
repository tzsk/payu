<?php

namespace Tzsk\Payu\Model;

use Illuminate\Database\Eloquent\Model;

class PayuPayment extends Model
{
    /**
     * Assignable array.
     *
     * @var array
     */
    protected $fillable = [
        'account', 'payable_id', 'payable_type', 'txnid', 'mihpayid', 'firstname', 'email', 'phone', 'amount',
        'discount', 'net_amount_debit', 'data', 'status', 'unmappedstatus', 'mode', 'bank_ref_num',
        'bankcode', 'cardnum', 'name_on_card', 'issuing_bank', 'card_type'
    ];

    /**
     * Manage Timestamps.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * PayuPayment constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('payu.table');
    }

    /**
     * Polymorphic relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function payable()
    {
        return $this->morphTo();
    }

    /**
     * Is the payment Captured?
     *
     * @return bool
     */
    public function isCaptured()
    {
        return ($this->unmappedstatus == 'captured');
    }

    /**
     * Get from Data Attribute.
     *
     * @param $item
     * @return null|mixed
     */
    public function get($item)
    {
        $data = $this->getData();

        return empty($data->$item) ? null : $data->$item;
    }

    /**
     * Get Original Payment Data.
     *
     * @return array
     */
    public function getData()
    {
        return json_decode($this->data);
    }

    /**
     * Local Transaction ID.
     *
     * @return string
     */
    public function getTransactionIdAttribute()
    {
        return $this->txnid;
    }

    /**
     * Payu Payment ID.
     *
     * @return string
     */
    public function getPaymentIdAttribute()
    {
        return $this->mihpayid;
    }

    /**
     * Total Amount Debited.
     *
     * @return double
     */
    public function getTotalAmountAttribute()
    {
        return $this->net_amount_debit;
    }

    /**
     * Get payment capture status.
     *
     * @return string
     */
    public function getCaptureStatusAttribute()
    {
        return $this->unmappedstatus;
    }

    /**
     * Get Bank Reference Number.
     *
     * @return string
     */
    public function getBankReferenceNumberAttribute()
    {
        return $this->bank_ref_num;
    }

    /**
     * Get Bank Code.
     *
     * @return string
     */
    public function getBankCodeAttribute()
    {
        return $this->bankcode;
    }

    /**
     * Get Card Number.
     *
     * @return string
     */
    public function getCardNumberAttribute()
    {
        return $this->cardnum;
    }
}
