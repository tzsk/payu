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
}
