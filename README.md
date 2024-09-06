# A Laravel Toolkit with basics tools

[![Packagist Version](https://img.shields.io/packagist/v/wsssoftware/laraveltoolkit)](https://packagist.org/packages/wsssoftware/laraveltoolkit)
[![NPM Version](https://img.shields.io/npm/v/laraveltoolkit)](https://www.npmjs.com/package/laraveltoolkit)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/wsssoftware/laraveltoolkit/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/wsssoftware/laraveltoolkit/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/wsssoftware/laraveltoolkit/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/wsssoftware/laraveltoolkit/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![codecov](https://codecov.io/gh/wsssoftware/laraveltoolkit/graph/badge.svg?token=nzaXcoyc3q)](https://codecov.io/gh/wsssoftware/laraveltoolkit)
[![Packagist Downloads](https://img.shields.io/packagist/dt/wsssoftware/laraveltoolkit?label=Packagist%20downloads)](https://packagist.org/packages/wsssoftware/laraveltoolkit)
[![NPM Downloads](https://img.shields.io/npm/d18m/laraveltoolkit?label=NPM%20downloads)](https://www.npmjs.com/package/laraveltoolkit)

A helpful and useful tools for Laravel projects

## Installation Laravel

You can install the package via composer:

```bash
composer require wsssoftware/laraveltoolkit
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laraveltoolkit-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laraveltoolkit-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laraveltoolkit-views"
```

## Installation JS

```bash
npm i -D laraveltoolkit
```

To Laravel Toolkit be able to compile its tailwind you must add this line on tailwind config file

```js
    content: [
        // ...
        './node_modules/laraveltoolkit/resources/**/*.{js,vue,ts}',
        // ...
    ],
```

## Usage

```php
$laravelToolkit = new LaravelToolkit();
echo $laravelToolkit->echoPhrase('Hello, Allan Mariucci Carvalho!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Allan Mariucci Carvalho](https://github.com/wsssoftware)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
