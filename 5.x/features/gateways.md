# Gateways

[[toc]]

## Switching Gateway

Laravel PayU includes support for switching to different gateway configured in your laravel configuration at runtime.

```php
return Payu::initiate($transaction)
    ->via('money') // <- Configured Gateway in your config file.
    ->redirect(route('status'));
```

If you don't explicitly specify the `via()` gateway then it will pickup the default gateway defined in your payu config file.

:::tip Caution

If the gateway is not configured properly or does not exist, it will throw a `ValidationException`. This will redirect the user back with the easily accessible validation message.
:::
