<?php
namespace Tzsk\Payu\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Tzsk\Payu\Model\PayuPayment;
use Tzsk\Payu\ProcessPayment;

class PaymentController extends Controller
{
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
    protected  function getPaymentFormInformation(Request $request)
    {
        $form_fields = $this->getFormFields($request);

        $prefix = (config('payu.env') == 'secure') ? 'secure' : 'test';
        $url = "https://{$prefix}.".config('payu.endpoint');

        $payment = (object) ['fields' => $form_fields, 'url' => $url];

        return $payment;
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function getHashChecksum(Request $request)
    {
        $fields = array_merge(config('payu.required_fields'), config('payu.optional_fields'));

        $hash_array = [];
        foreach (collect($fields)->flip()->except(['phone'])->flip() as $field) {
            $hash_array[] = $request->has($field) ? $request->get($field) : "";
        }

        $checksum_array = array_merge([config('payu.key')], $hash_array, [config('payu.salt')]);

        $hash = hash('sha512', implode('|', $checksum_array));

        return $hash;
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function validateRequest(Request $request)
    {
        $validation = collect(array_flip(config('payu.required_fields')))->map(function($value) {
            return 'required';
        });

        $data = $this->getFormDataArray($request);

        $validator = Validator::make($request->all(), $validation->all());

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
        $model = Cache::get('tzsk_model');

        if (config('payu.driver') == 'database') {
            $payment_instance = PayuPayment::create($attributes);

            if (!empty($model)) {
                $payment_instance->fill([
                    'payable_id' => $model->id,
                    'payable_type' => get_class($model)
                ])->save();
            }

            $payment = $payment_instance->id;

        } else {
            $payment = $attributes;
        }

        Cache::put('tzsk_payment', $payment, 5);
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
        $items = collect(config('payu.required_fields'))->merge(config('payu.optional_fields'))
            ->merge(config('payu.additional_fields'));

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
        $status_url = Cache::get('tzsk_status_url');

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
        $data = Cache::get('tzsk_data');
        $status_url = $this->getStatusUrl();

        $request->replace($data);
        $validation = $this->validateRequest($request);
        $hash = $this->getHashChecksum($request);

        $redirect = collect(config('payu.redirect'))->map(function($value) use ($request, $status_url) {
            $separator = str_contains($value, '?') ? '&' : '?';
            return url($value.$separator.'_token='.csrf_token().'&'.'callback='.$status_url);
        })->all();

        $form_fields = array_merge(['key' => config('payu.key'), 'hash' => $hash],
            array_merge($redirect, $validation));

        return $form_fields;
    }

}
