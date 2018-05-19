<?php

namespace Tzsk\Payu\Helpers;

class Redirector
{
    /**
     * @var string
     */
    protected $url;

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

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }
}
