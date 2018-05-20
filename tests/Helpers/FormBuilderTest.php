<?php

namespace Tzsk\Payu\Tests\Helpers;

use Tzsk\Payu\Tests\TestCase;
use Tzsk\Payu\Helpers\FormBuilder;
use Illuminate\Support\Facades\Session;

class FormBuilderTest extends TestCase
{
    public function testItWillBuildWithCorrectCredentials()
    {
        $credentials = $this->getCredentials();

        $builder = (new FormBuilder(request()))->build();
        $this->assertTrue(is_object($builder));
        $this->assertTrue(is_array($builder->fields));
        $this->assertNotEmpty($builder->url);
    }

    public function testWillFailWithWrongCredentials()
    {
        $credentials = $this->getCredentials(false);

        try {
            $builder = (new FormBuilder(request()))->build();
        } catch (\Exception $e) {
            $this->assertNotEmpty($e->getMessage());
        }
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
