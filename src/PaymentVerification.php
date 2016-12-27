<?php
namespace Tzsk\Payu;


use Tzsk\Payu\Model\PayuPayment;

class PaymentVerification
{
    /**
     * Command to Pass to the PayuService.
     *
     * @var string
     */
    protected $command = 'verify_payment';

    /**
     * Merchant Key.
     *
     * @var string|null
     */
    protected $key = null;

    /**
     * Merchant Salt.
     *
     * @var string|null
     */
    protected $salt = null;

    /**
     * Payu Service URL.
     *
     * @var string
     */
    protected $url = null;

    /**
     * Transaction ID.
     *
     * @var array
     */
    protected $txn_id = [];

    /**
     * Original Response.
     *
     * @var array
     */
    protected $response = [];


    /**
     * PaymentVerification constructor.
     *
     * @param $txn_id
     */
    public function __construct($txn_id)
    {
        $this->txn_id = $txn_id;
        $this->key = config('payu.key');
        $this->salt = config('payu.salt');

        $env = config('payu.env');
        if ($env != 'test') {
            $env = 'info';
        }
        $this->url = "https://{$env}.payu.in/merchant/postservice?form=2";
    }

    /**
     * Request for Verification Status.
     *
     * @return $this
     */
    public function request()
    {
        $this->sendRequest();

        $this->updatePayuTransaction();

        return $this;
    }

    /**
     * Simple data accessor.
     *
     * @return array
     */
    public function simple()
    {
        return $this->getResponse(true);
    }

    /**
     * Full Data Accessor.
     *
     * @return array
     */
    public function full()
    {
        return $this->getResponse(false);
    }

    /**
     * Get Response according to Simple or Full.
     *
     * @param boolean $simple
     * @return array
     */
    protected function getResponse($simple)
    {
        if (empty($this->response['status'])) {
            return ['status' => false, 'message' => $this->response['msg']];
        }

        if ($simple) {
            return $this->getSimpleResponseData();
        }

        return $this->getFullResponseData();
    }

    /**
     * Get the Request Params for Verification.
     *
     * @return string
     */
    public function getVerificationPostFields()
    {
        $txn_string = implode("|", $this->txn_id);
        $hash_str = $this->key.'|'.$this->command.'|'.$txn_string.'|'.$this->salt;
        $hash = strtolower(hash('sha512', $hash_str));
        $params = ['key' => $this->key, 'hash' => $hash, 'var1' => $txn_string, 'command' => $this->command];

        return http_build_query($params);
    }

    /**
     * Set the Verification Response.
     *
     * @throws \Exception
     */
    protected function sendRequest()
    {
        $post_fields = $this->getVerificationPostFields();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new \Exception(curl_error($curl));
        }
        curl_close($curl);

        $this->response = json_decode($output, true);
    }

    /**
     * Update Payu Payments Table Entries.
     *
     * @return boolean
     */
    protected function updatePayuTransaction()
    {
        if (config('payu.driver') != 'database') {
            return false;
        }

        foreach ($this->txn_id as $item) {
            $payu_payment = PayuPayment::where('txnid', $item)->first();
            if ($payu_payment) {
                $attributes = $this->response['transaction_details'][$item];
                $attributes['status'] = ($this->response['transaction_details'][$item]['status'] == 'success') ?
                    ProcessPayment::STATUS_COMPLETED : ProcessPayment::STATUS_FAILED;

                $payu_payment->fill($attributes)->save();
            }
        }

        return true;
    }

    /**
     * Get Full Response for user.
     *
     * @return array
     */
    protected function getFullResponseData()
    {
        $data = ['status' => true, 'data' => []];
        foreach ($this->txn_id as $item) {
            $status = ($this->response['transaction_details'][$item]['status'] == 'success') ?
                ProcessPayment::STATUS_COMPLETED : ProcessPayment::STATUS_FAILED;

            $message = $this->getResponseMessage($item);

            $data['data'][$item] = ['status' => $status, 'message' => $message,
                'response' => $this->response['transaction_details'][$item]];
        }

        return $data;
    }

    /**
     * Get Simple Response for user.
     *
     * @return array
     */
    protected function getSimpleResponseData()
    {
        $data = ['status' => true, 'data' => []];
        foreach ($this->txn_id as $item) {
            $status = ($this->response['transaction_details'][$item]['status'] == 'success') ? true : false;

            $message = $this->getResponseMessage($item);

            $data['data'][$item] = ['status' => $status, 'message' => $message];
        }

        return $data;
    }

    /**
     * Get Response Message.
     *
     * @param $item
     * @return string
     */
    protected function getResponseMessage($item)
    {
        $message = @$this->response['transaction_details'][$item]['error_Message'];
        if (count($this->response['transaction_details'][$item]) < 3) {
            $message = 'Transaction ID not found.';
        }

        return $message;
    }

}