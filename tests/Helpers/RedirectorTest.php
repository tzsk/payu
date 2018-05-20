<?php

namespace Tzsk\Payu\Tests\Helpers;

use Tzsk\Payu\Tests\TestCase;
use Tzsk\Payu\Helpers\Redirector;
use Illuminate\Routing\Redirector as BaseRedirector;

class RedirectorTest extends TestCase
{
    protected $redirector;

    public function setUp()
    {
        parent::setUp();

        $this->redirector = new Redirector();
    }

    public function testItShouldPerformToRouteActionMethods()
    {
        $to = $this->redirector->redirectTo('foo')->getUrl();
        $action = $this->redirector->redirectAction('Tzsk\Payu\Controllers\PaymentController@index')->getUrl();
        $route = $this->redirector->redirectRoute('tzsk.payu.payment')->getUrl();

        $this->assertEquals(url('foo'), $to);
        $this->assertEquals(url('tzsk/payment'), $action);
        $this->assertEquals(url('tzsk/payment'), $route);
    }
}
