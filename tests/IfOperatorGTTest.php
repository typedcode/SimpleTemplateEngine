<?php

include_once './tests/TemplateEngineTestBase.php';
include_once './tests/TestObject.php';

class IfOperatorGTTest extends TemplateEngineTestBase {

    public function testCompareHardcodedValueToHardcodedValueFlase() {
        $result = $this->engine->parse( "if/operators/gt/val_val_false.html" );

        $this->assertEquals( "", $result );
    }

    public function testCompareHardcodedValueToHardcodedValueTrue() {
        $result = $this->engine->parse( "if/operators/gt/val_val_true.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testCompareHardcodedValueToVariableValueFalse() {
        $this->engine->assign( "var1", 12 );

        $result = $this->engine->parse( "if/operators/gt/val_var.html" );

        $this->assertEquals( "", $result );
    }

    public function testCompareHardcodedValueToVariableValueTrue() {
        $this->engine->assign( "var1", 4 );

        $result = $this->engine->parse( "if/operators/gt/val_var.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testCompareVariableValueToHardcodedValueFalse() {
        $this->engine->assign( "var1", 4 );

        $result = $this->engine->parse( "if/operators/gt/var_val.html" );

        $this->assertEquals( "", $result );
    }

    public function testCompareVariableValueToHardcodedValueTrue() {
        $this->engine->assign( "var1", 12 );

        $result = $this->engine->parse( "if/operators/gt/var_val.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testCompareVariableValueToOtherVariableValueFalse() {
        $this->engine->assign( "var1", 8 );
        $this->engine->assign( "var2", 15 );

        $result = $this->engine->parse( "if/operators/gt/var_var.html" );

        $this->assertEquals( "", $result );
    }

    public function testCompareVariableValueToOtherVariableValueTrue() {
        $this->engine->assign( "var1", 15 );
        $this->engine->assign( "var2", 15 );

        $result = $this->engine->parse( "if/operators/gt/var_var.html" );

        $this->assertEquals( "", $result );
    }

    public function testFirstParameterNotNumeric() {
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( "ERROR evaluating if Clause. One Value is not Numeric: string | 15" );
        $this->engine->assign( "var1", "string" );
        $this->engine->assign( "var2", 15 );

        $this->engine->parse( "if/operators/gt/var_var.html" );
    }

    public function testSecondParameterNotNumeric() {
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( "ERROR evaluating if Clause. One Value is not Numeric: 15 | string" );

        $this->engine->assign( "var1", 15 );
        $this->engine->assign( "var2", "string" );

        $this->engine->parse( "if/operators/gt/var_var.html" );
    }
}

?>