<?php

include_once './tests/TemplateEngineTestBase.php';
include_once './tests/TestObject.php';

class IfOperatorNETest extends TemplateEngineTestBase {

    public function testCompareHardcodedValueToHardcodedVarFalse() {
        $result = $this->engine->parse( "if/operators/ne/val_val_false.html" );

        $this->assertEquals( "", $result );
    }

    public function testCompareHardcodedValueToHardcodedVarTrue() {
        $result = $this->engine->parse( "if/operators/ne/val_val_true.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testCompareHardcodedValueToVariableValueFalse() {
        $this->engine->assign( "var1", 8 );

        $result = $this->engine->parse( "if/operators/ne/val_var.html" );

        $this->assertEquals( "", $result );
    }

    public function testCompareHardcodedValueToVariableValueTrue() {
        $this->engine->assign( "var1", 12 );

        $result = $this->engine->parse( "if/operators/ne/val_var.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testCompareVariableValueToHardcodedValueFalse() {
        $this->engine->assign( "var1", 8 );

        $result = $this->engine->parse( "if/operators/ne/var_val.html" );

        $this->assertEquals( "", $result );
    }

    public function testCompareVariableValueToHardcodedValueTrue() {
        $this->engine->assign( "var1", 12 );

        $result = $this->engine->parse( "if/operators/ne/var_val.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testCompareVariableValueToVariableValueFalse() {
        $this->engine->assign( "var1", 7 );
        $this->engine->assign( "var2", 7 );

        $result = $this->engine->parse( "if/operators/ne/var_var.html" );

        $this->assertEquals( "", $result );
    }
    public function testCompareVariableValueToVariableValueTrue() {
        $this->engine->assign( "var1", 4 );
        $this->engine->assign( "var2", 8 );

        $result = $this->engine->parse( "if/operators/ne/var_var.html" );

        $this->assertEquals( "yes", $result );
    }
}

?>