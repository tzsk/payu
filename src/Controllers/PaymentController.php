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

        if (config('payu.driver') == 'database') {
            $payment = PayuPayment::create($attributes)->id;
        } else {
            $payment = $attributes;
        }

        Cache::put('tzsk_payment', $payment, 5);

        return redirect()->to($request->callback);
    }

    /**
     * @param  Request $request
     * @return object
     */
    protected  function getPaymentFormInformation(Request $request)
    {
        $data = Cache::get('tzsk_data');
        $status_url = Cache::get('tzsk_status_url');

        $request->replace($data);
        $validation = $this->validateRequest($request);
        $hash = $this->getHashChecksum($request);

        $redirect = collect(config('payu.redirect'))->map(function($value) use ($request, $status_url) {
            $seperator = str_contains($value, '?') ? '&' : '?';
            return url($value.$seperator.'callback='.$status_url);
        })->all();

        $form_fields = array_merge(['key' => config('payu.key'), 'hash' => $hash],
            array_merge($redirect, $validation));

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
        list($validation, $data) = $this->getValidationData($request);

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        return $data;
    }

    /**
     * @param  Request $request
     * @return array
     */
    protected function getValidationData(Request $request)
    {
        $validation = [];
        $data = [];
        foreach (config('payu.required_fields') as $item) {
            $validation[$item] = 'required';
            $request->has($item) ? $data[$item] = $request->get($item) : null;
        }

        foreach (config('payu.optional_fields') as $item) {
            $request->has($item) ? $data[$item] = $request->get($item) : null;
        }

        foreach (config('payu.additional_fields') as $item) {
            $request->has($item) ? $data[$item] = $request->get($item) : null;
        }

        return compact($validation, $data);
    }

}
