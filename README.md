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
