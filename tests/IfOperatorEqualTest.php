<?php

include_once './tests/TemplateEngineTestBase.php';
include_once './tests/TestObject.php';

class IfOperatorEqualTest extends TemplateEngineTestBase {

    public function testCompareTwoHardcodedValuesWithEachOtherFalse() {
        $this->assertEquals( "", $this->engine->parse( "if/operators/eq/val_val_false.html" ) );
    }

    public function testCompareTwoHardcodedValuesWithEachOtherTrue() {
        $this->assertEquals( "yes", $this->engine->parse( "if/operators/eq/val_val_true.html" ) );
    }

    public function testCompareHardcodedValueToVarValueFalse() {

        $this->engine->assign( "var1", "wrongValue" );

        $result = $this->engine->parse( "if/operators/eq/val_var.html" );

        $this->assertEquals( "", $result );
    }

    public function testCompareHardcodedValueToVarValueTrue() {

        $this->engine->assign( "var1", "value" );

        $result = $this->engine->parse( "if/operators/eq/val_var.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testCompareVarValueToHardcodedValueFalse() {
        $this->engine->assign( "var1", "wrongValue" );

        $result = $this->engine->parse( "if/operators/eq/var_val.html" );

        $this->assertEquals( "", $result );
    }

    public function testCompareVarValueToHardcodedValueTrue() {
        $this->engine->assign( "var1", "value" );

        $result = $this->engine->parse( "if/operators/eq/var_val.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testCompareVarValueToVarValueFalse() {
        $this->engine->assign( "var1", "value" );
        $this->engine->assign( "var2", "otherValue" );

        $result = $this->engine->parse( "if/operators/eq/var_var.html" );

        $this->assertEquals( "", $result );
    }

    public function testCompareVarValueToVarValueTrue() {
        $this->engine->assign( "var1", "value" );
        $this->engine->assign( "var2", "value" );

        $result = $this->engine->parse( "if/operators/eq/var_var.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testVarDoesNotExist(){
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( "TEMPLATE ERROR: 'var' ist not assigned.");
        $this->engine->parse( "if/operators/eq/var_notExists.html" );
    }

    public function testCompareObjectFunctionResultToObjectVariableFalse() {
        $obj = new TestObject();
        $obj->publicVar = "value";

        $obj2 = new TestObject();
        $obj2->publicVar = "wrongValue";

        $this->engine->assign( "var1", $obj );
        $this->engine->assign( "var2", $obj2 );

        $result = $this->engine->parse( "if/operators/eq/objVar_objFunction.html" );

        $this->assertEquals( "", $result );
    }

    public function testCompareObjectFunctionResultToObjectVariableTrue() {
        $obj = new TestObject();
        $obj->publicVar = "value";

        $obj2 = new TestObject();
        $obj2->publicVar = "value";

        $this->engine->assign( "var1", $obj );
        $this->engine->assign( "var2", $obj2 );

        $result = $this->engine->parse( "if/operators/eq/objVar_objFunction.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testCompareObjectVariableToObjectFunctionResultFalse() {
        $obj = new TestObject();
        $obj->publicVar = "value";

        $obj2 = new TestObject();
        $obj2->publicVar = "wrongValue";

        $this->engine->assign( "var1", $obj );
        $this->engine->assign( "var2", $obj2 );

        $result = $this->engine->parse( "if/operators/eq/objVar_objFunction.html" );

        $this->assertEquals( "", $result );
    }

    public function testCompareObjectVariableToObjectFunctionResultTrue() {
        $obj = new TestObject();
        $obj->publicVar = "value";

        $obj2 = new TestObject();
        $obj2->publicVar = "value";

        $this->engine->assign( "var1", $obj );
        $this->engine->assign( "var2", $obj2 );

        $result = $this->engine->parse( "if/operators/eq/objVar_objFunction.html" );

        $this->assertEquals( "yes", $result );
    }
}

?>