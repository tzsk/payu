<?php

namespace Tzsk\Payu\Tests\Helpers;

use Tzsk\Payu\Tests\TestCase;
use Tzsk\Payu\Helpers\Processor;
use Illuminate\Support\Facades\Session;
use Tzsk\Payu\Model\PayuPayment;
use Illuminate\Http\Request;

class ProcessorTest extends TestCase
{
    public function testWillReturnPayuPaymentObject()
    {
        $credentials = $this->getCredentials();
        $processor = (new Processor(request()))->process();

        $this->assertTrue(is_array($processor));
    }

    protected function getCredentials($transaction = true)
    {
        $data = [
            'data' => $transaction ? $this->getTransaction() : [],
            'status_url' => 'foo',
            'account' => 'payubiz',
            'model' => ['id' => 1, 'class' => 'baz'],
        ];

        Session::put('tzsk_payu_data', $data);

        return $data;
    }

    protected function getTransaction()
    {
        return [
            'txnid' => strtoupper(str_random(8)),
            'amount' => rand(100, 999),
            'productinfo' => 'Product Information',
            'firstname' => 'John',
            'email' => 'john@doe.com',
            'phone' => '9876543210',
        ];
    }
}
