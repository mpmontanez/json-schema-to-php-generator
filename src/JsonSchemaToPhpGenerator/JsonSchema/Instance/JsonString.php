<?php

namespace mpmontanez\JsonSchemaToPhpGenerator\JsonSchema\Instance;

/**
 * Class String
 * @package mpmontanez\JsonSchemaToPhpGenerator\JsonSchema\Instance
 *
 * A string of Unicode code points, from the JSON "string" production
 */
class JsonString extends Instance {

    public function __construct($value = null)
    {
        $this->key = 'string';
        $this->phpDataType = 'string';
        $this->value = $value;
    }

}