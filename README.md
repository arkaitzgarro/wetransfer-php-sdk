# WeTransfer PHP SDK

[![Build Status](https://travis-ci.org/arkaitzgarro/wetransfer-php-sdk.svg?branch=master)](https://travis-ci.org/arkaitzgarro/wetransfer-php-sdk)
[![Latest Stable Version](https://poser.pugx.org/wetransfer/php-sdk/v/stable)](https://packagist.org/packages/wetransfer/php-sdk)
[![License](https://poser.pugx.org/wetransfer/php-sdk/license)](https://packagist.org/packages/wetransfer/php-sdk)
[![Coverage Status](https://coveralls.io/repos/github/arkaitzgarro/wetransfer-php-sdk/badge.svg?branch=master)](https://coveralls.io/github/arkaitzgarro/wetransfer-php-sdk?branch=master)

A PHP SDK for WeTransfer's Public API

## Installation

System Requirements
- PHP 5.6.4 or greater
- Composer

The WeTransfer PHP SDK can be installed through Composer.

```bash
$ php composer require arkaitzgarro/wetransfer-php-sdk
```

## Usage

In order to be able to use the SDK and access our public APIs, you must provide an API key, which is available in our [Developers Portal](https://developers.wetransfer.com/).

You can find a complete working example [here](https://github.com/arkaitzgarro/wetransfer-php-sdk/blob/master/example/CreateTransfer.php).

Firstly, the client needs to be configured with your API Key obtained from the WeTransfer's Developer.

```php
use WeTransfer\Client as WetransferClient;

$wtClient = new WetransferClient($_SERVER['WT_API_KEY']);
```

### Transfer

Transfers can be created with or without items. Once the transfer has been created, items can be added at any time:

```php
$transfer = $wtClient->createTransfer('My Transfer', 'And optional description');
```

### Add items to a transfer

Once a transfer has been created you can then add items (files or links) to it. If you are adding files to the transfer, the files are not uploaded at this point, but in the next step.

```php
$wtClient->addLinks($transfer, [
  [
    'url' => 'https://en.wikipedia.org/wiki/Japan',
    'meta' => [
      'title' => 'Japan'
    ]
  ]
]);

$wtClient->addFiles($transfer, [
  [
    'filename' => 'Japan-01.jpg',
    'filesize' => 13370099
  ]
]);
```

The `$transfer` object will be updated with each item that was added to the transfer. For files, this objects will be used to upload the correspondent file to the transfer, as explained in the next section.

### Upload a file

Once the file has been added to the transfer, next step is to upload the file or files. You must provide the content of the file to upload as a reference (use `fopen` function for it), we will NOT read the file for you. The content of the file will be splited and uploaded in chunks of 5MB to our S3 bucket.

```php
foreach($transfer->getFiles() as $file) {
  $file->upload(fopen(realpath('./path/to/your/files.jpg'), 'r'));
}
```

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
