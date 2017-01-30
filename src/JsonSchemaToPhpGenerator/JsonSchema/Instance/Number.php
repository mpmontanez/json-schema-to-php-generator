<?php

namespace mpmontanez\JsonSchemaToPhpGenerator\JsonSchema\Instance;

/**
 * Class Number
 * @package mpmontanez\JsonSchemaToPhpGenerator\JsonSchema\Instance
 *
 * An arbitrary-precision, base-10 decimal number value, from the JSON "number" production
 */
class Number extends Instance {

    public function __construct($value = null)
    {
        $this->key = 'number';
        $this->phpDataType = 'int';
        $this->value = $value;
    }

}