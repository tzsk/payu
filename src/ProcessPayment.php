<?php
namespace Tzsk\Payu;


use Illuminate\Http\Request;

class ProcessPayment
{
    /**
     * Payment statuses.
     */
    const STATUS_COMPLETED = 'Completed';
    const STATUS_PENDING   = 'Pending';
    const STATUS_FAILED    = 'Failed';
    const STATUS_TAMPERED  = 'Tampered';

    /**
     * Request returned from gateway.
     *
     * @var Request
     */
    protected $request;

    /**
     * ProcessPayment constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the status of the Payment.
     *
     * @return string
     */
    public function getStatus()
    {
        if ($this->checksumIsValid()) {
            switch (strtolower($this->request->status)) {
                case 'success':
                    return self::STATUS_COMPLETED;
                    break;

                case 'pending':
                    return self::STATUS_PENDING;
                    break;

                case 'failure':

                default:
                    return self::STATUS_FAILED;
            }
        }

        return self::STATUS_TAMPERED;
    }

    /**
     * Get Transaction ID of the transaction.
     *
     * @return null|string
     */
    public function getTransactionId()
    {
        return $this->request->has('mihpayid') ? (string) $this->request->mihpayid : null;
    }

    /**
     * Get the hash returned by the gateway.
     *
     * @return null|string
     */
    protected function getHash()
    {
        return $this->request->has('hash') ? (string) $this->request->hash : null;
    }

    /**
     * Is the returned has valid?
     *
     * @return bool
     */
    protected function checksumIsValid()
    {
        $fields = array_merge(config('payu.required_fields'), config('payu.optional_fields'));

        $reverse_fields = array_reverse(array_merge(['key'], $fields, ['status', 'salt']));
        $result_array = array_merge($this->request->all(), ['salt' => config('payu.salt')]);
        $values = array_map(
            function($paramName) use ($result_array) {
                return array_key_exists($paramName, $result_array) ? $result_array[$paramName] : '';
            },
            $reverse_fields
        );

        return hash('sha512', implode('|', $values)) === $this->getHash();
    }

}