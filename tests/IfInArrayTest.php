<?php

include_once './tests/TemplateEngineTestBase.php';
include_once './tests/TestObject.php';

class IfInArrayTest extends TemplateEngineTestBase {

    public function testFunctionValueInArrayFalse() {
        $testObject = new TestObject();

        $this->engine->assign( "arrayObject", $testObject );

        $result = $this->engine->parse( "if/inArray/inArray_array_by_function_value_false.html" );
        $this->assertEquals( "no", $result );
    }

    public function testFunctionValueInArrayTrue() {
        $testObject = new TestObject();

        $this->engine->assign( "arrayObject", $testObject );

        $result = $this->engine->parse( "if/inArray/inArray_array_by_function_value.html" );
        $this->assertEquals( "yes", $result );
    }

    public function testArrayFromFunctionComparedToVarValueFalse() {
        $testObject = new TestObject();

        $this->engine->assign( "arrayObject", $testObject );
        $this->engine->assign( "value", "wrong");

        $result = $this->engine->parse( "if/inArray/inArray_array_by_function_var.html" );

        $this->assertEquals( "no", $result );
    }

    public function testArrayFromFunctionComparedToVarValueTrue() {
        $testObject = new TestObject();

        $this->engine->assign( "arrayObject", $testObject );
        $this->engine->assign( "value", "value3");

        $result = $this->engine->parse( "if/inArray/inArray_array_by_function_var.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testArrayFromFunctionComparedToValueFromFunctionFalse() {
        $valueObject = new TestObject();
        $valueObject->setValue( "value30" );

        $this->engine->assign( "arrayObject", new TestObject() );
        $this->engine->assign( "value", $valueObject );

        $result = $this->engine->parse( "if/inArray/inArray_array_by_function_var_function.html" );

        $this->assertEquals( "no", $result );
    }

    public function testArrayFromFunctionComparedToValueFromFunctionTrue() {
        $valueObject = new TestObject();
        $valueObject->setValue( "value3" );

        $this->engine->assign( "arrayObject", new TestObject() );
        $this->engine->assign( "value", $valueObject );

        $result = $this->engine->parse( "if/inArray/inArray_array_by_function_var_function.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testArrayComparedToHardcodedValueFalse() {
        $this->engine->assign( "array", array( "value1", "value2", "value3", "value4", "value5" ) );

        $result = $this->engine->parse( "if/inArray/inArray_array_value_false.html" );

        $this->assertEquals( "no", $result );
    }

    public function testArrayComparedToHardcodedValueTrue() {
        $this->engine->assign( "array", array( "value1", "value2", "value3", "value4", "value5" ) );

        $result = $this->engine->parse( "if/inArray/inArray_array_value.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testArrayComparedToVariableFalse() {
        $this->engine->assign( "array", array( "value1", "value2", "value3", "value4", "value5" ) );
        $this->engine->assign( "value", "wrong");

        $result = $this->engine->parse( "if/inArray/inArray_array_var.html" );

        $this->assertEquals( "no", $result );
    }

    public function testArrayComparedToVariableTrue() {
        $this->engine->assign( "array", array( "value1", "value2", "value3", "value4", "value5" ) );
        $this->engine->assign( "value", "value3");

        $result = $this->engine->parse( "if/inArray/inArray_array_var.html" );

        $this->assertEquals( "yes", $result );
    }
}

?>