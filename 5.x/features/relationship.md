# Relationship

[[toc]]

## Example Scenario

Let's assume that in your application you accept payment for products and you generate Invoices and you receive payments against them.

```php
use Illuminate\Database\Model;
use Tzsk\Payu\Models\HasTransactions;

class Invoice extends Model
{
    use HasTransactions;
}
```

### Usage

Now while making payment you have an `Invoice` depending on what the user is buying. So during payment you can specify the Entity that you are making payment against.

```php
use Tzsk\Payu\Concerns\Attributes;
use Tzsk\Payu\Concerns\Customer;
use Tzsk\Payu\Concerns\Transaction;
use Tzsk\Payu\Facades\Payu;

// Your invoice
$invoice = Invoice::find(1);

$customer = Customer::make()
    ->firstName('John Doe')
    ->email('john@example.com');

// Associate the transaction with your invoice
$transaction = Transaction::make()
    ->charge($invoice->amount)
    ->for('Order of iPhone 12')
    ->against($invoice)
    ->to($customer);

return Payu::initiate($transaction)->redirect(route('status'));
```

### Benefits

Now when you receive the payment and accept it, you get a `Tzsk\Payu\Models\PayuTransactions` model object.

```php
$transaction = Payu::capture();

$transaction->paidFor // Order
```

### Trait

Also, if you look closely there's a `HasTransactions` trait that you can use with your model. That gives you power to find transactions against the Invoice.

```php
$invoice = Invoice::find(1);

$invoice->transactions()->get(); // Collection of PayuTransactions

// The successful transaction for that order
$transaction = $invoice->transactions()->successful()->first();

// The failed transaction for that order
$transaction = $invoice->transactions()->failed()->first();

// The pending transaction for that order
$transaction = $invoice->transactions()->pending()->first();

// The invalid transaction for that order
$transaction = $invoice->transactions()->invalid()->first();
```

## Polymorphism

Polymorphism portrays an example in object-oriented programming where methods in various classes that do similar things should have a similar name. Polymorphism is essentially an OOP pattern that enables numerous classes with different functionalities to execute or share a common Interface.

Even if your application has multiple Entity Types that accept payment. Laravel PayU can handle any of them with the polymorphic relationship in the Database. Say you have another entity `Subscription` that you accept payment for.

```php
use Illuminate\Database\Model;
use Tzsk\Payu\Models\HasTransactions;

class Subscription extends Model
{
    use HasTransactions;
}
```

You can also accept payments for this the same way. And it works as you would expect.

```php
$subscription = Subscription::find(1);

$customer = Customer::make()
    ->firstName('John Doe')
    ->email('john@example.com');

// Associate the transaction with your subscription
$transaction = Transaction::make()
    ->charge($subscription->amount)
    ->for('Silver Plan $30')
    ->against($subscription)
    ->to($customer);

return Payu::initiate($transaction)->redirect(route('status'));
```
