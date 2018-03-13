# Verifying Payment

If in case the Amount is deducted from the payee's account but for some reason it did not redirect back to your Callback URL. In those cases you might like to Check if a payment is really done or not. In that case you might want to update your database that the payment was successful.

I recommend that you save your Transaction ID with the Order or Subscription.

```php
$response = Payment::verify($transaction); 
# String containing single Transaction ID or an array of Transaction IDs.

# For Multi Account Payment.

$response = Payment::via('ACCOUNT_NAME')->verify($transaction);
```
**Return:**
```php
[
    "status" => true,
    
    "data" => ["TXN_ID" => PayuPayment::class], 
    # Array of PayuPayment instances with Transaction ID.

    "message" => "Something went wrong." 
    # If status = false then there will be some message.
]
```

The above response will return the `status` and a collection of `PayuPayment` model instances. Where you can perform the exact same functionalities as you can with individual payments.