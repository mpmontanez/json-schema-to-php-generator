<?php

namespace mpmontanez\JsonSchemaToPhpGenerator\JsonSchema\Instance;

/**
 * Class Boolean
 * @package mpmontanez\JsonSchemaToPhpGenerator\JsonSchema\Instance
 *
 * A "true" or "false" value, from the JSON "true" or "false" productions
 */
class Boolean extends Instance
{
    public function __construct($value = false)
    {
        $this->key = 'boolean';
        $this->phpDataType = 'bool';
        $this->value = $value;
    }
}