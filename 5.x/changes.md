# About 5.x

[[toc]]

Laravel PayU 5.x has been completely rewritten keeping in mind all the goodies that latest Laravel provides.

## Compatibility

:::tip Warning

**Laravel PayU 5.x** is not backward compatible with **4.x** So make sure you read the Upgrade Guide to know if you can upgrade to the latest version.
:::

## Database Driver
Previously it did support a `Session` driver. Which was good on its own but it limited the capabilities of Laravel PayU quite a lot. So in `5.x` it is all database driver only. Which is a good thing for audits and other retry capabilities.

## Fluent Interface

Now the entire payment api has been changed to conform to objects. It now segments various concerns like `Customer`, `Transaction` and `Attributes`. 

Also, the gateways has been implemented using Polymorphism with different classes like `PayuBiz` and `PayuMoney` rather than passing simple array key.

## New Commands

### Publish

Now the publishing config migration or view has been made quite simple by running `payu:publish`. You can find more on that by running `php artisan payu:publish --help`

### Payment Verification

Now it comes with a new `payu:verify` command. Which verifies the `Failed` & `Pending` transactions. More on that can be found in the [Features / Verification](features/verification.html) section.
