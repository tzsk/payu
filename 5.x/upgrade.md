# Upgrade Guide

[[toc]]

If you are using older version of Laravel PayU with `Session` driver, then this would be much easier. Otherwise you might have some difficulties importing old `payu_payments` database table into the new `payu_transactions` table.

## System Requirements

- You should be running `PHP >= 7.4`.
- You need to have Laravel `>= 7.x` for Laravel PayU `5.x`

## Steps to Upgrade

### Step - 1: Update Composer Dependency

First off, you will need to update your composer dependency. 

- Change the version in your `composer.json` to make it look like `"tzsk/payu": "^5.0"`.
- After that you should run `composer update` to update the `lock` file.

### Step - 2: Cleanup Config

The config file structure and the migration also changed significantly so it is better to delete them to start fresh.

- Delete old `config/payu.php` config file.

:::tip Backup

Make sure you backup your `payu_payments` table data and gateway config so that you can easily setup again.
:::

### Step - 3: Publish New Config / Migration

Once you have cleared old files publish the new config and database migration file.

```bash
php artisan payu:publish --config --migration

php artisan migrate
```

### Step - 4: Restore Config

Once you migrated the new `payu_transactions` table you just need to configure the new `config/payu.php` file.

### Step - 5: Make Payment

Refactor the place where you make the payment in your application and change it to the new Fluent Interface according to the [Usage](/usage)

### Step - 6: Import Old Data

I've created a Gist on github which defines a Command file which just dumps all the old transactions to the new table. It attaches all the old entries to the correct gateway entry in your newly setup config file.

- Gist File: [PayuMigrateCommand](https://github.com/tzsk)

This command file should expose an artisan command `php artisan payu:migrate`. Place it inside the Commands folder of your Laravel App and you should be able to run in. If you have custom namespace setup for your app don't forget to change the namespace of the file.

:::tip Heads Up

Make sure you update your config and set the default you have been using in your old setup. And depending on how many records you have in your old payments table this might take a while.
:::

### Step - 7: Change Model (Polymorphic)

This only applies if you are using the Polymorphic relation that 4.x provided.

- Now the new trait that Laravel PayU exposes is `Tzsk\Payu\Models\HasTransactions`.
- And change the occurrences where you used the relationship `$entity->payments()` to `$entity->transactions()`
- Also where ever you have fetched reverse relation from `PayuPayment` model instance like: `$payment->payable()`, update that to the new `$transaction->paidFor()` relation.

## Done

Finally you are all done, If you want you can keep the old `payu_payments` table or get rid of that. It's totally up to you.
