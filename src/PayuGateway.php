<?php
namespace Tzsk\Payu;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Tzsk\Payu\Model\PayuPayment;

class PayuGateway
{
    /**
     * Callback URL.
     *
     * @var
     */
    protected $url;

    /**
     *
     * @param array $data
     * @param $callback
     * @return \Illuminate\Http\RedirectResponse
     */
    public function make(array $data, $callback)
    {
        call_user_func($callback, $this);
        Cache::put('tzsk_data', $data, 5);
        Cache::put('tzsk_status_url', $this->url, 5);

        return redirect()->to('tzsk/payment');
    }

    /**
     * Receive Payment and Return Payment Model.
     *
     * @return PayuPayment
     */
    public function capture()
    {
        $pay = Cache::get('tzsk_payment');

        if (config('payu.driver') == 'database') {
            return PayuPayment::find($pay);
        }

        return new PayuPayment($pay);
    }

    /**
     * Set Redirect URL.
     *
     * @param $url
     * @param array $parameters
     * @param null $secure
     * @return $this
     */
    public function redirectTo($url, $parameters = [], $secure = null)
    {
        $this->url = url($url, $parameters, $secure);

        return $this;
    }

    /**
     * Set Redirect Action.
     *
     * @param $action
     * @param array|null $parameters
     * @param bool $absolute
     * @return $this
     */
    public function redirectAction($action, $parameters = [], $absolute = true)
    {
        $this->url = action($action, $parameters, $absolute);

        return $this;
    }

    /**
     * Set Redirect Action.
     *
     * @param $route
     * @param array|null $parameters
     * @param bool $absolute
     * @return $this
     */
    public function redirectRoute($route, $parameters = [], $absolute = true)
    {
        $this->url = route($route, $parameters, $absolute);

        return $this;
    }

}
