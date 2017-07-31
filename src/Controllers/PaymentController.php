<?php
namespace Tzsk\Payu\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Tzsk\Payu\Helpers\Config;
use Tzsk\Payu\Helpers\Storage;
use Tzsk\Payu\Model\PayuPayment;
use Tzsk\Payu\ProcessPayment;

class PaymentController extends Controller
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * PaymentController constructor.
     */
    public function __construct()
    {
        $this->config = new Config();
        $this->storage = new Storage();
    }

    /**
     * Got to payment.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $payment = $this->getPaymentFormInformation($request);

        return view('tzsk::payment_form', compact('payment'));
    }

    /**
     * After payment it will return here.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function payment(Request $request)
    {
        $attributes = $request->only([
            'txnid', 'mihpayid', 'firstname', 'email', 'phone', 'amount', 'discount',
            'net_amount_debit', 'data', 'status', 'unmappedstatus', 'mode', 'bank_ref_num',
            'bankcode', 'cardnum', 'name_on_card', 'issuing_bank', 'card_type'
        ]);
        $attributes['data'] = json_encode($request->all());
        $process = new ProcessPayment($request);
        $attributes['status'] = $process->getStatus();

        $this->generatePaymentObject($attributes);

        return redirect()->to(base64_decode($request->callback));
    }

    /**
     * @param  Request $request
     * @return object
     * @throws \Exception
     */
    protected function getPaymentFormInformation(Request $request)
    {
        $form_fields = $this->getFormFields($request);

        $prefix = ($this->config->getEnv() == 'secure') ? 'secure' : 'test';
        $url = "https://{$prefix}.".$this->config->getEndpoint();

        $payment = (object) ['fields' => $form_fields, 'url' => $url];

        return $payment;
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function getHashChecksum(Request $request)
    {
        $fields = array_merge($this->config->getRequiredFields(), $this->config->getOptionalFields());

        $hash_array = [];
        foreach (collect($fields)->flip()->except(['phone'])->flip() as $field) {
            $hash_array[] = $request->has($field) ? $request->get($field) : "";
        }

        $checksum_array = array_merge([$this->config->getKey()], $hash_array, [$this->config->getSalt()]);

        $hash = hash('sha512', implode('|', $checksum_array));

        return $hash;
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function validateRequest(Request $request)
    {
        $validation = collect(array_flip($this->config->getRequiredFields()))->map(function () {
            return 'required';
        })->all();

        $data = $this->getFormDataArray($request);

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        return $data;
    }

    /**
     * Generate the payment model Object for use.
     *
     * @param $attributes
     */
    protected function generatePaymentObject($attributes)
    {
        $model = $this->storage->getModel();

        if ($this->config->getDriver() == 'database') {
            $payment_instance = PayuPayment::create($attributes);

            if (!empty($model)) {
                $payment_instance->fill([
                    'payable_id' => $model['id'],
                    'payable_type' => $model['class']
                ])->save();
            }

            $payment = $payment_instance->id;
        } else {
            $payment = $attributes;
        }

        Session::put('tzsk_payu_data.payment', $payment);
    }

    /**
     * Get the form data array.
     *
     * @param Request $request
     * @return array
     */
    private function getFormDataArray(Request $request)
    {
        $data = [];
        $items = collect($this->config->getRequiredFields())->merge($this->config->getOptionalFields())
            ->merge($this->config->getAdditionalFields());

        foreach ($items as $item) {
            $request->has($item) ? $data[$item] = $request->get($item) : null;
        }

        return $data;
    }

    /**
     * Get Status url.
     *
     * @return string
     * @throws \Exception
     */
    private function getStatusUrl()
    {
        $status_url = $this->storage->getStatusUrl();

        if (empty($status_url)) {
            throw new \Exception("There is no Redirect URL specified.");
        }

        return urlencode(base64_encode($status_url));
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getFormFields(Request $request)
    {
        $data = $this->storage->getData();
        $status_url = $this->getStatusUrl();

        $request->replace($data);
        $validation = $this->validateRequest($request);
        $hash = $this->getHashChecksum($request);

        $redirect = collect($this->config->getRedirect())->map(function ($value) use ($request, $status_url) {
            $separator = str_contains($value, '?') ? '&' : '?';
            return url($value.$separator.'_token='.csrf_token().'&'.'callback='.$status_url);
        })->all();

        $form_fields = array_merge(['key' => $this->config->getKey(), 'hash' => $hash],
            array_merge($redirect, $validation));

        return $form_fields;
    }
}
