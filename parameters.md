# Payment Parameters

Below are the possible parameters you can send to the Payu gateway. 

**NOTE:** If you use your custom parameter it will be ignored.

List of Parameters:

**Required Parameters:**

- `txnid`       ~ `string` ~ Transaction ID.
- `amount`      ~ `double` ~ Amount to be charged.
- `productinfo` ~ `string` ~ Product Information,
- `firstname`   ~ `string` ~ Payee First Name.
- `email`       ~ `string` ~ Payee Email Address.
- `phone`       ~ `string` ~ Payee Phone Number.

**Optional Parameters:**

- `lastname`    ~ `string` ~ Payee Last Name.
- `address1`    ~ `string` ~ Address Line 1.
- `address2`    ~ `string` ~ Address Line 2.
- `city`        ~ `string` ~ Payee City.
- `state`       ~ `string` ~ Payee State.
- `country`     ~ `string` ~ Payee Country.
- `zipcode`     ~ `string` ~ Payee Zip Code.

**And More:**

These are the fields which are used for Custom Parameter to be sent along with the Payment for whatever reason. You can get these items later to identify which item this Payment is associated to or any other scenario.
These are all optional too.

- `udf1` ~ `string` ~ Anything you want to send/receive later.
- `udf2` ~ `string` ~ Anything you want to send/receive later.
- `udf3` ~ `string` ~ Anything you want to send/receive later.
- `udf4` ~ `string` ~ Anything you want to send/receive later.
- `udf5` ~ `string` ~ Anything you want to send/receive later.