<?php

class TestObject {

    public $publicVar;

    private $foreachOutput;
    private $value;

    public function testOutput() {
        return "testoutput";
    }

    public function foreachOutput() {
        return $this->foreachOutput;
    }

    public function setForEachOutput( $output ) {
        $this->foreachOutput = $output;
    }

    public function getPublicVar() {
        return $this->publicVar;
    }

    public function getArray() {
        return array( "value1", "value2", "value3", "value4", "value5" );
    }

    public function setValue( $value ) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }
}

?>