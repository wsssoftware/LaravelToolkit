# A Laravel Toolkit with basics tools

[![Packagist Version](https://img.shields.io/packagist/v/wsssoftware/laraveltoolkit)](https://packagist.org/packages/wsssoftware/laraveltoolkit)
[![NPM Version](https://img.shields.io/npm/v/laraveltoolkit)](https://www.npmjs.com/package/laraveltoolkit)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/wsssoftware/laraveltoolkit/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/wsssoftware/laraveltoolkit/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/wsssoftware/laraveltoolkit/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/wsssoftware/laraveltoolkit/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![codecov](https://codecov.io/gh/wsssoftware/laraveltoolkit/graph/badge.svg?token=nzaXcoyc3q)](https://codecov.io/gh/wsssoftware/laraveltoolkit)
[![Packagist Downloads](https://img.shields.io/packagist/dt/wsssoftware/laraveltoolkit?label=Packagist%20downloads)](https://packagist.org/packages/wsssoftware/laraveltoolkit)
[![NPM Downloads](https://img.shields.io/npm/d18m/laraveltoolkit?label=NPM%20downloads)](https://www.npmjs.com/package/laraveltoolkit)

A helpful and useful tools for Laravel projects integrated with Vue, Inertia, Primevue and others.

## Installation Laravel

You can install the package via composer:

```bash
composer require wsssoftware/laraveltoolkit
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laraveltoolkit-config"
```

You can publish the sitemap config file with:

```bash
php artisan vendor:publish --tag="laraveltoolkit-sitemap"
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
    ]
```

## Usage

### [ACL](docs/ACL.md)
A minimalist implementation of an access control level

### [FLASH](docs/FLASH.md)
Simple flash messages from backend to front end.

### [FILEPOND](docs/FILEPOND.md)
A bridge between FilePond and Laravel

### [LINK](docs/LINK.md)
Based on Inertia link but with some new feats.

### [SEO](docs/SEO.md)
Tools to help dev to handle with SEO features.

### [SITEMAP](docs/SITEMAP.md)
A toolkit to automatically generate sitemaps for application 

### [STORED ASSETS](docs/STORED_ASSETS.md)
Tools to help handle with storing assets. 

### [THEME SWITCHER](docs/THEME.md)
A JS class that handles theme changes

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
