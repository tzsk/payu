# Payment Response API

After you capture the payment you will get `PayuPayment` Model instance returned to you.

**NOTE:** It is just a wrapper on the Original Payu Response. You can get the original response returned to you as JSON also.

Possible Attributes:

- `$payment->isCaptured();` ~ `boolean` ~ Is the payment captured or failed.
- `$payment->transaction_id;` ~ `string` ~ Your Local Transaction ID.
- `$payment->payment_id;` ~ `string` ~ PayU Payment ID.
- `$payment->total_amount;` ~ `double` ~ Get Total Amount Deducted.
- `$payment->bank_reference_number;` ~ `string|null` ~ Issued Bank Reference Number.
- `$payment->bank_code;` ~ `string|null` ~ Issued Bank Code.
- `$payment->card_number;` ~ `string|null` ~ Redacted Card Number. If paid through Card.
- `$payment->getData();` ~ `json` ~ Get the full response from Gateway.

There are also many other columns available in the Table. See the `payu_payments` table for more.

There are some new features:

- `$payment->account` ~ `string` ~ From which account the payment is made.
- `$payment->get('key')` ~ `string` ~ Get the other optional field values from the Original Response.

Example:
`$payment->get('udf1')` ~ Will return the value you sent in `udf1` field.

OR...

`$payment->get('zipcode')` ~ Will return the Payee Zip Code.

Basically, if you see the full response through `$payment->getData()`. All the keys can be collected through this API.