<?php

namespace Tzsk\Payu\Tests\Helpers;

use Tzsk\Payu\Tests\TestCase;
use Illuminate\Support\Facades\Session;
use Tzsk\Payu\Helpers\Storage;

class StorageTest extends TestCase
{
    public function testItWillHaveSimpleStorageFields()
    {
        $data = $this->getSimpleData();
        $storage = new Storage();

        $this->assertEquals($data['status_url'], $storage->getStatusUrl());
        $this->assertEquals($data['payment'], $storage->getPayment());
        $this->assertNotEmpty($storage->getAccount());
        $this->assertEmpty($storage->getModel());
        $this->assertTrue(is_array($storage->getData()));
    }

    public function testItWillHaveCustomExtendedStorageFields()
    {
        $data = $this->getExtendedData();
        $storage = new Storage();

        $this->assertNotEmpty($storage->getAccount());
        $this->assertTrue(is_array($storage->getModel()));
        $this->assertTrue(is_array($storage->getData()));
    }

    public function getExtendedData()
    {
        $data = [
            'data' => [],
            'status_url' => 'foo',
            'account' => 'bar',
            'model' => ['id' => 1, 'class' => 'baz'],
            'payment' => 'foo'
        ];

        Session::put('tzsk_payu_data', $data);

        return $data;
    }

    public function getSimpleData()
    {
        $data = [
            'data' => [],
            'status_url' => 'foo',
            'account' => null,
            'model' => null,
            'payment' => 'foo'
        ];

        Session::put('tzsk_payu_data', $data);

        return $data;
    }
}
