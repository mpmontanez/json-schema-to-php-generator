<?php

class GeneratorTest extends PHPUnit_Framework_TestCase
{

    public function testGenerate()
    {
        $generator = new \mpmontanez\JsonSchemaToPhpGenerator\Generator();
        $result = $generator->generate();

        $this->assertTrue($result);
    }

}
