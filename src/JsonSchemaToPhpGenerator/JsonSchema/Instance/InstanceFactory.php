<?php

namespace mpmontanez\JsonSchemaToPhpGenerator\JsonSchema\Instance;

use mpmontanez\JsonSchemaToPhpGenerator\Exceptions\UnknownJsonSchemaTypeException;

class InstanceFactory {

    public static function buildInstance($key, $value = null)
    {
        if ($key == 'number') {
            return new Number($value);
        }
        elseif ($key == 'object') {
            return new Object($value);
        }
        elseif ($key == 'string') {
            return new JsonString($value);
        }
        elseif ($key == 'boolean') {
            return new Boolean($value);
        }
        else if ($key == 'null') {
            return new Null();
        }

        throw new UnknownJsonSchemaTypeException('Unknown JSON schema type: ' . $key);
    }

}