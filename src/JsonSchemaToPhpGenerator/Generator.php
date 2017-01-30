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
use mpmontanez\JsonSchemaToPhpGenerator\JsonSchema\Instance\InstanceFactory;

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
        $this->refreshDestinationDir($this->config['json-schemas']['dest-dir']);

        // Read JSON schema file(s) from a source directory.
        $jsonSchemaFiles = $this->fileSystem->listContents($this->config['json-schemas']['source-dir']);
        foreach ($jsonSchemaFiles as $jsonSchemaFile) {
            $this->generatePhpCodeFromSchemaPathAndWriteFiles($jsonSchemaFile['path']);
        }

        return true;
    }

    protected function refreshDestinationDir($destinationDir)
    {
        $this->fileSystem->deleteDir($destinationDir);
        $this->fileSystem->createDir($destinationDir);
    }

    protected function generatePhpCodeFromSchemaPathAndWriteFiles($schemaPath)
    {
        $jsonSchema = $this->fileSystem->read($schemaPath);
        $schema = json_decode($jsonSchema, true);

        // Generate code.
        $code = $this->generatePhpCodeFromSchema($schema);

        // Write .php files to a destination directory.
        $fileName = $this->determineClassNameFromSchemaTitle($schema['title']) . '.php';
        $filePath = $this->config['json-schemas']['dest-dir'] . '/' . $fileName;
        $this->fileSystem->write($filePath, $code);
    }

    protected function generatePhpCodeFromSchema($schema)
    {
        $type = $schema['type'];

        if ($type === 'object') {
            return $this->preprendPhpFileHeader($this->generatePhpClassFromSchema($schema));
        }

        throw new UnknownJsonSchemaTypeException('Unknown schema type: ' . $type);
    }

    protected function preprendPhpFileHeader($code)
    {
        return '<?php ' . PHP_EOL . PHP_EOL . $code;
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
            $jsonSchemaInstance = InstanceFactory::buildInstance($property['type']);

            // Private property attributes.
            $class->setProperty(PhpProperty::create($name)
                ->setVisibility('private')
                ->setType($jsonSchemaInstance->getPhpDataType())
            );

            // Getter method(s).
            $class->setMethod(PhpMethod::create($this->buildGetterMethodNameFromString($name))
                ->setDescription('Provides the ' . $name . ' attribute.')
                ->setBody('return $this->' . $name . ';')
            );
        }

        $generator = new CodeGenerator();
        return $generator->generate($class);
    }

    protected function buildGetterMethodNameFromString($str)
    {
        // Convert snake-case to camel-case.
        $parts = explode('_', $str);
        foreach ($parts as $index => $part) {
            $parts[$index] = ucfirst($part);
        }
        $str = join('', $parts);

        return lcfirst($str);
    }

    protected function determineClassNameFromSchemaTitle($schemaTitle)
    {
        return str_replace(' ', '', $schemaTitle);
    }

}
