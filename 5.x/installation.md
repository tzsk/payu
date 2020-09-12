# Installation

[[toc]]

## Installing Laravel PayU

Installation is quite straight forward like every other laravel package out there.

### Composer Installation

It is advised to use Composer to install Laravel PayU into your new Laravel project:

```bash
composer require tzsk/payu
```

After the installation is complete you can access the commands that Laravel PayU provides.

### Publish Migration & Migrate

You should publish the migration and create the table that comes out of the box for managing Payments.

```bash
php artisan payu:publish --migration
// Or..
php artisan payu:publish -m

php artisan migrate
```

### Publish Config

If you want to tweak the configuration of your PayU integration you can choose to publish the config file.

```bash
php artisan payu:publish --config
// Or..
php artisan payu:publish -c
```

### Publish Template

Laravel PayU comes with a default loading screen that comes out of the box with the package. If you want to modify that intermediate loading screen you do so by publishing the view.

```bash
php artisan payu:publish --template
// Or..
php artisan payu:publish -t
```

:::tip Keep In Mind

After publishing the view you will see a `<x-payu-form />` component in there. That is the magic component responsible for making the payment. You can completely change the design of the page however you like, but make sure to keep that component.
:::

### Publish Template

Laravel PayU comes with a default loading screen that comes out of the box with the package. If you want to modify that intermediate loading screen you do so by publishing the view.

```bash
php artisan payu:publish --template
// Or..
php artisan payu:publish -t
```

### Publish Everything

If you wish to publish everything that is mentioned above you can choose to do so all in one go.

```bash
php artisan payu:publish --all
// Or..
php artisan payu:publish -a
```
