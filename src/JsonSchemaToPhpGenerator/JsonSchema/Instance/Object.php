<?php

namespace mpmontanez\JsonSchemaToPhpGenerator\JsonSchema\Instance;

/**
 * Class Object
 * @package mpmontanez\JsonSchemaToPhpGenerator\JsonSchema\Instance
 *
 * An unordered set of properties mapping a string to an instance, from the JSON "object" production
 */
class Object extends Instance
{
    public function __construct($value = null)
    {
        $this->key = 'object';
        $this->phpDataType = '\stdClass';
        $this->value = $value;
    }
}