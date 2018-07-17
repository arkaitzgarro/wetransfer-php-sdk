# WeTransfer PHP SDK

A PHP SDK for WeTransfer's Public API

## Installation

System Requirements
- PHP 5.6.4 or greater
- Composer
- The mbstring extension

The WeTransfer PHP SDK can be installed through Composer.

```bash
$ php composer require arkaitzgarro/wetransfer-php-sdk
```

## Usage

In order to be able to use the SDK and access our public APIs, you must provide an API key, which is available in our [Developers Portal](https://developers.wetransfer.com/).

You can find a complete working example [here](https://github.com/arkaitzgarro/wetransfer-php-sdk/blob/master/example/CreateTransfer.php).

## Development

Get Composer. Follow the instructions defined on the official [Composer page](https://getcomposer.org/doc/00-intro.md), or if you are using `homebrew`, just run:

```bash
$ brew install composer
```

Install project dependencies:

```bash
$ composer install
```

Run the test suite:

```bash
$ ./vendor/bin/phpunit
```
