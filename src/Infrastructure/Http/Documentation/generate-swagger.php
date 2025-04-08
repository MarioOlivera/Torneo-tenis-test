<?php
require __DIR__.'/../../../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__.'/../../../../');
$dotenv->load();

define('BASE_URL', $_ENV['BASE_URL']);

$scanDirs = [
    __DIR__.'/../Controllers',  
    __DIR__.'/../Schemas'
];

$openapi = \OpenApi\Generator::scan($scanDirs);

file_put_contents(__DIR__.'/../../../../public/openapi.yaml', $openapi->toYaml());

echo "Documentación generada en ".realpath(__DIR__.'/../../../../public/openapi.yaml')."\n";