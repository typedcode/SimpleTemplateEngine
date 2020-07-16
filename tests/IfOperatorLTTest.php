<?php

include_once './tests/TemplateEngineTestBase.php';
include_once './tests/TestObject.php';

class IfOperatorLTTest extends TemplateEngineTestBase {

    public function testComparesHardcodedValueToOtherHardcodedValueFalse() {
        $result = $this->engine->parse( "if/operators/lt/val_val_false.html" );

        $this->assertEquals( "", $result );
    }

    public function testComparesHardcodedValueToOtherHardcodedValueTrue() {
        $result = $this->engine->parse( "if/operators/lt/val_val_true.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testCompareHardcodedValueToVarValueFalse() {
        $this->engine->assign( "var1", 4 );

        $result = $this->engine->parse( "if/operators/lt/val_var.html" );

        $this->assertEquals( "", $result );
    }

    public function testCompareHardcodedValueToVarValueTrue() {
        $this->engine->assign( "var1", 12 );

        $result = $this->engine->parse( "if/operators/lt/val_var.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testCompareVariableValueToHardcodedValueFalse() {
        $this->engine->assign( "var1", 12 );

        $result = $this->engine->parse( "if/operators/lt/var_val.html" );

        $this->assertEquals( "", $result );
    }

    public function testCompareVariableValueToHardcodedValueTrue() {
        $this->engine->assign( "var1", 4 );

        $result = $this->engine->parse( "if/operators/lt/var_val.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testCompareVariableValueToVariableValueFlase() {
        $this->engine->assign( "var1", 15 );
        $this->engine->assign( "var2", 7 );

        $result = $this->engine->parse( "if/operators/lt/var_var.html" );

        $this->assertEquals( "", $result );
    }

    public function testCompareVariableValueToVariableValueTrue() {
        $this->engine->assign( "var1", 4 );
        $this->engine->assign( "var2", 8 );

        $result = $this->engine->parse( "if/operators/lt/var_var.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testFirstParameterNotNumeric() {
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( "ERROR evaluating if Clause. One Value is not Numeric: string | 15" );

        $this->engine->assign( "var1", "string" );
        $this->engine->assign( "var2", 15 );

        $this->engine->parse( "if/operators/lt/var_var.html" );
    }

    public function testSecondParameterNotNumeric() {
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( "ERROR evaluating if Clause. One Value is not Numeric: 15 | string" );

        $this->engine->assign( "var1", 15 );
        $this->engine->assign( "var2", "string" );

        $this->engine->parse( "if/operators/lt/var_var.html" );
    }
}

?>