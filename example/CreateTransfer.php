#!/usr/bin/env php
<?php
$autoload = null;

$autoloadFiles = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php'
];

foreach ($autoloadFiles as $autoloadFile) {
    if (file_exists($autoloadFile)) {
        $autoload = $autoloadFile;
        break;
    }
}

if (!$autoload) {
    echo "Autoload file not found; try 'composer dump-autoload' first." . PHP_EOL;
    exit(1);
}

require $autoload;

echo "Authenticating...\n";
WeTransfer\Client::setApiKey(getenv('WT_API_KEY'));

echo "Creating a transfer...\n";
$transfer = WeTransfer\Transfer::create('Test transfer');

echo "Adding a link...\n";
$transfer = WeTransfer\Transfer::addLinks($transfer, [
    [
        'url' => 'https://en.wikipedia.org/wiki/Japan',
        'meta' => [
            'title' => 'Japan'
        ]
    ]
]);

echo "Adding a file...\n";
$transfer = WeTransfer\Transfer::addFiles($transfer, [
    [
        'filename' => 'Japan-01.jpg',
        'filesize' => 13370099
    ]
]);

echo "Uploading a file...\n";
foreach ($transfer->getFiles() as $file) {
    WeTransfer\File::upload($file, fopen(realpath('./example/files/Japan-01.jpg'), 'r'));
}

echo "Transfer URL: {$transfer->getShortenedUrl()}\n";
