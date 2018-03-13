# Configuration Options

After you publish the config file by running `vendor:publish` you will get a `config/payu.php` configuration file created. You can set all your config there.

## General Configuration

- **`env`:** Which environment are you running your gateway. Possible values: `test`, `secure`. Default: `test`.
- **`default`:** Default account to use for payment. Should be one of below `accounts` array keys. Default: `payubiz`
- **`accounts`** This is an array to hold the accounts and their credentials. You can use multiple `PayuBiz` or `PayuMoney` account if you desire. See the [Multi Account Payment](/usage?id=multi-account-payment) section in the usage.
    - **`key`:** The merchant key for that specific account.
    - **`salt`:** The merchant salt for that specific account.
    - **`auth`:** The  authorization header for the account. Available for `PayuMoney` only.
    - **`money`:** If it is a `PayuMoney` account then `true` otherwise `false`.
- **`driver`:** Which driver to use. Possible options: `session`, `database`. Default: `session`.
- **`table_name`:** If you are using database driver then what is the table name? Default: `payu_payments`

## Database Driver Configuration

Now, if you want to use `database` driver for payment then you will have to first publish the migration file as stated in the [Configure](/getting-started?id=configuration) section.

That command will create a migration file in your migration folder: `database/migrations`.

Then, if you already have a table named: `payu_payments` then change the database name in the Migration File provided.
After that run the below command to migrate the database:

```bash
$ php artisan migrate
```

It will create the table in your databse. And you are all set to use `database` driver.