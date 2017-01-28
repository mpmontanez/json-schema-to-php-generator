<?php

namespace mpmontanez\JsonSchemaToPhpGenerator;

use gossi\codegen\generator\CodeGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\model\PhpProperty;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use mpmontanez\JsonSchemaToPhpGenerator\Exceptions\UnknownJsonSchemaTypeException;

class Generator 
{
    protected $config;
    protected $fileSystem;

    public function __construct()
    {
        $this->config = [
            'json-schemas' => [
                'source-dir' => 'json-schemas',
                'dest-dir'   => 'generated-code'
            ],
            'generated-code' => [
                'namespace' => 'Sample\\GeneratedCode\\'
            ],
            'application' => ['root-dir' => __DIR__ . '/../../']
        ];

        $adapter = new Local(__DIR__ . '/../../');
        $this->fileSystem = new Filesystem($adapter);
    }

    public function generate()
    {
        // Empty the destination directory.
        $this->fileSystem->deleteDir($this->config['json-schemas']['dest-dir']);
        $this->fileSystem->createDir($this->config['json-schemas']['dest-dir']);

        // Read JSON schema file(s) from a source directory.
        $jsonSchemaFiles = $this->fileSystem->listContents($this->config['json-schemas']['source-dir']);
        foreach ($jsonSchemaFiles as $jsonSchemaFile) {
            $contents = $this->fileSystem->read($jsonSchemaFile['path']);
            $schema = json_decode($contents, true);

            // Generate code.
            $code = $this->generatePhpCodeFromSchema($schema);

            // Write .php files to a destination directory.
            $fileName = $this->determineClassNameFromSchemaTitle($schema['title']) . '.php';
            $filePath = $this->config['json-schemas']['dest-dir'] . '/' . $fileName;
            $this->fileSystem->write($filePath, $code);
        }

        return true;
    }

    protected function generatePhpCodeFromSchema($schema)
    {
        $type = $schema['type'];

        if ($type === 'object') {
            return $this->generatePhpClassFromSchema($schema);
        }

        throw new UnknownJsonSchemaTypeException('Unknown schema type: ' . $type);
    }

    protected function generatePhpClassFromSchema($schema)
    {
        $className = $this->determineClassNameFromSchemaTitle($schema['title']);

        $class = new PhpClass();

        // Class base.
        $class
            ->setQualifiedName($this->config['generated-code']['namespace'] . $className)
            ->setDescription('Auto-generated class from JSON schema file.')
        ;

        // Class properties.
        $properties = !empty($schema['properties']) ? $schema['properties'] : [];
        foreach ($properties as $name => $property) {
            $class->setProperty(PhpProperty::create($name)
                ->setVisibility('private')
                ->setType('string')
            );
        }

        $generator = new CodeGenerator();
        return '<?php ' . PHP_EOL . PHP_EOL . $generator->generate($class);
    }

    protected function determineClassNameFromSchemaTitle($schemaTitle)
    {
        return str_replace(' ', '', $schemaTitle);
    }
}
