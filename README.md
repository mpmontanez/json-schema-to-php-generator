# JSON Schema to PHP Generator

Objective: build a code generation tool that will convert JSON spec files into simple PHP classes. Java inspiration: https://github.com/joelittlejohn/jsonschema2pojo

## Demo
Generate sample generated-code by executing the following:

```
php cli/generate.php
```

The schema source directory will be the 'json-schemas' folder and the code will be placed in the 'generated-code' folder.

## Installation
```
{
    "require": {
        "mpmontanez/json-schema-to-php-generator": "dev-master"
    }
}
```

## Usage
To generate PHP code from a set of JSON schema files, provide the following paramters to the Generator: the base working directory, the JSON schema source folder name, the generated code destination folder name, and the base namespace to use for the generated PHP classes.

*BEWARE* The initial contents of the destination folder will be deleted when calling generate().
```
$generator = new \mpmontanez\JsonSchemaToPhpGenerator\Generator(
    __DIR__ . '/../', 'json-schemas', 'generated-code', 'Sample\\GeneratedCode\\');
$generator->generate();
```
