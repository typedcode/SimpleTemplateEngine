<?php

include_once './tests/TemplateEngineTestBase.php';
include_once './tests/TestObject.php';

class VarSubstitutionTest extends TemplateEngineTestBase{

    public function testObjectFunctionSubstitution() {
        $this->engine->assign( "obj", new TestObject() );

        $result = $this->engine->parse( "varSubstitution/objectFunction.html" );

        $this->assertEquals( "testoutput", $result);
    }

    public function testObjectVarSubstitution() {
        $obj = new TestObject();
        $obj->key = "public Var Value";

        $this->engine->assign( "obj", $obj );

        $result = $this->engine->parse( "varSubstitution/objectVar.html" );

        $this->assertEquals( "public Var Value", $result);
    }

    public function testSingleVarSubstitution() {
        $this->engine->assign( "varValue", "replaced value" );

        $result = $this->engine->parse( "varSubstitution/varSubstitution.html" );

        $this->assertEquals( "replaced value", $result );
    }

    public function testMultipleVarSubstitutions() {
        $this->engine->assign( "varValue", "var" );
        $this->engine->assign( "var2Value", "var2" );

        $result = $this->engine->parse( "varSubstitution/varSubstitution_2.html" );

        $this->assertEquals( "var\n" .
                             "var2\n" .
                             "var", $result );
    }

    public function testArraySubstitution() {
        $this->engine->assign( "obj", array( "key" => "value" ) );

        $result = $this->engine->parse( "varSubstitution/objectVar.html" );

        $this->assertEquals( "value", $result );
    }

    public function testVarNotAssigned() {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage( "TEMPLATE ERROR: 'obj' ist not assigned." );
        $this->engine->parse( "varSubstitution/objectVar.html" );
    }

    public function testArrayAssociative() {
        $array = array( "key" => "value" );
        $this->engine->assign( "array", $array );

        $result = $this->engine->parse( "varSubstitution/arrayAssociative.html" );

        $this->assertEquals( "value", $result );
    }

    public function testArrayIndex() {
        $array = array();
        array_push( $array, "value" );
        $this->engine->assign( "array", $array );

        $result = $this->engine->parse( "varSubstitution/arrayIndex.html" );

        $this->assertEquals( "value", $result );
    }

    public function testSessionSubstitution() {
        session_start();
        $_SESSION[ "sessionVar" ] = "sessionValue";
        $this->setUp();
        
        $this->assertEquals( "sessionValue", $this->engine->parse( "varSubstitution/sessionVariables.html") );

        session_destroy();
    }
}

?>
