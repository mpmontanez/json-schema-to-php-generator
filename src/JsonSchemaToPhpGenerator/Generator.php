<?php

namespace mpmontanez\JsonSchemaToPhpGenerator;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class Generator 
{
    protected $config;
    protected $fileSystem;

    public function __construct()
    {
        $this->config = [
            'json-schemas' => ['source-dir' => 'json-schemas'],
            'application' => ['root-dir' => __DIR__ . '/../../']
        ];

        $adapter = new Local(__DIR__ . '/../../');
        $this->fileSystem = new Filesystem($adapter);
    }

    public function generate()
    {
        // Read JSON schema file(s).
        $jsonSchemaFiles = $this->fileSystem->listContents($this->config['json-schemas']['source-dir']);
        foreach ($jsonSchemaFiles as $jsonSchemaFile) {
            $contents = $this->fileSystem->read($jsonSchemaFile['path']);
            // @todo Parse Json contents and convert into PHP classes.
        }

        return true;
    }

}
