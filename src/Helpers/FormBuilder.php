<?php

namespace Tzsk\Payu\Helpers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormBuilder
{
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
     * FormBuilder Constructor
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->storage = new Storage();
        $this->config = new Config($this->storage->getAccount());
        $this->request = $request;
    }

    /**
     * @return object
     */
    public function build()
    {
        return (object) ['fields' => $this->getFields(), 'url' => $this->config->getPaymentUrl()];
    }

    /**
     * @return array
     */
    protected function getFields()
    {
        $this->request->replace($this->storage->getData());

        $redirect = collect($this->config->getRedirect())->map(function ($value) {
            $separator = Str::contains($value, '?') ? '&' : '?';
            return url($value . $separator . '_token=' . csrf_token() . '&' . 'callback=' . $this->getStatusUrl());
        })->all();

        return array_merge(
            ['key' => $this->config->getKey(), 'hash' => $this->getHashChecksum()],
            array_merge($redirect, $this->validateRequest()),
            $this->config->isPayuMoney() ? ['service_provider' => 'payu_paisa'] : []
        );
    }

    /**
     * @return string
     */
    protected function getStatusUrl()
    {
        $status_url = $this->storage->getStatusUrl();

        if (empty($status_url)) {
            throw new \Exception('There is no Redirect URL specified.');
        }

        return urlencode(base64_encode($status_url));
    }

    /**
     * @return string
     */
    protected function getHashChecksum()
    {
        $fields = array_merge($this->config->getRequiredFields(), $this->config->getOptionalFields());

        $hash_array = [];
        foreach (collect($fields)->flip()->except(['phone'])->flip() as $field) {
            $hash_array[] = $this->request->has($field) ? $this->request->get($field) : '';
        }

        $checksum_array = array_merge(
            [$this->config->getKey()],
            $hash_array,
            [$this->config->getSalt()]
        );

        return hash('sha512', implode('|', $checksum_array));
    }

    /**
     * @return array
     */
    protected function validateRequest()
    {
        $validation = collect(array_flip($this->config->getRequiredFields()))->map(function () {
            return 'required';
        })->all();

        $data = $this->getDataArray();

        $validator = Validator::make($this->request->all(), $validation);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        return $data;
    }

    /**
     * @return array
     */
    private function getDataArray()
    {
        $data = [];
        $items = collect($this->config->getRequiredFields())->merge($this->config->getOptionalFields())
            ->merge($this->config->getAdditionalFields());

        foreach ($items as $item) {
            $this->request->has($item) ? $data[$item] = $this->request->get($item) : null;
        }

        return $data;
    }
}
