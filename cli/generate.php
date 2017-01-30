<?php

require __DIR__.'/../vendor/autoload.php';

$generator = new \mpmontanez\JsonSchemaToPhpGenerator\Generator(
    __DIR__ . '/../', 'json-schemas', 'generated-code', 'Sample\\GeneratedCode\\');
$generator->generate();