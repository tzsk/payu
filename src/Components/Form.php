<?php

namespace Tzsk\Payu\Components;

use Illuminate\View\Component;

class Form extends Component
{
    public static array $injectedProps = [];

    public static function inject(array $props)
    {
        self::$injectedProps = $props;
    }

    public function data()
    {
        return array_merge(parent::data(), self::$injectedProps);
    }

    public function render()
    {
        return <<<'blade'
            <div style="display: none;">
                <form action="{{ $endpoint }}" id="payment_form" method="POST">
                    @foreach($fields as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>
            </div>

            <script>
                document.getElementById('payment_form').submit();
            </script>
        blade;
    }
}
