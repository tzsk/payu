# Concerns

[[toc]]

Latest Laravel PayU has fluent concern based design. You can setup your transaction more easily than remembering to pass an array with all the proper keys in the old setup.

## Customer

This is the most important concern. It represents who are you charging to.

```php
use Tzsk\Payu\Concerns\Customer;

$customer = Customer::make()
    ->firstName(string) // Required
    ->email(string) // Required
    ->phone(string) // Required for PayuMoney only
    ->lastName(string)
    ->addressOne(string)
    ->addressTwo(string)
    ->city(string)
    ->state(string)
    ->zipCode(string)
    ->country(string);
```

## Attributes

This is likely something you will never use in your integration, but payu allows you to pass up to 10 custom string values depending for any custom information that you want to attach with the payment that will help you to identify the payment context. None of these values are mandatory the entire object is not required to be passed if you are not using it.

```php
use Tzsk\Payu\Concerns\Attributes;

$attributes = Attributes::make()
    ->udf1()
    ->udf2()
    ->udf3()
    ...
    ->udf10();    
```

## Transaction

This is sort of like the container of Customer and Attributes but something more than that. This is required and needs to be passed with the `Payu::initiate($transaction)`

```php
use Tzsk\Payu\Concerns\Transaction;

$transaction = Transaction::make(string $transactionId)
    ->charge(float)
    ->for(string) // Product
    ->against(Model)
    ->with($attributes)
    ->to($customer);
```

- If you want to generate your own Transaction ID you can do that and pass it to the `make` method. However, this is optional.
  
  If you don't specify it, then Laravel PayU will generate a `Random 10 Character` Transaction ID for you.
- Next you need to specify the amount in float or int in the `charge()` method.
- You will also have to pass the Product Information in the `for()` method.
- The `against()` method is there to specify the Model that you are making the payment. Eg. `Invoice`. More on that in the [Relationship](relationship.html)
- You may choose not sto specify the attributes in the `with()` method.
- Finally you need to tell it who are you charging to i.e. The Customer inside the `to()` method.
