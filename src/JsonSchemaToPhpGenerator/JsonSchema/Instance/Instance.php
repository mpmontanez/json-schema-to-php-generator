<?php

namespace mpmontanez\JsonSchemaToPhpGenerator\JsonSchema\Instance;

abstract class Instance {

    protected $key = '';
    protected $phpDataType = null;
    protected $value = null;

    public function getKey() {
        return $this->key;
    }

    public function getPhpDataType() {
        return $this->phpDataType;
    }

    public function getValue() {
        return $this->value;
    }

}