<?php

include_once './tests/TemplateEngineTestBase.php';

class IfBooleanTest extends TemplateEngineTestBase {

    public function testEmbeddedIf1() {
        $this->engine->assign( "var1", true );
        $this->engine->assign( "var2", true );

        $result = $this->engine->parse( "if/boolean/embeddedIf.html" );
        $this->assertEquals( "onetwo", $result );
    }

    public function testEmbeddedIf2() {
        $this->engine->assign( "var1", true );
        $this->engine->assign( "var2", false );

        $result = $this->engine->parse( "if/boolean/embeddedIf.html" );
        $this->assertEquals( "one", $result );
    }

    public function testEmbeddedIf3() {
        $this->engine->assign( "var1", false );
        $this->engine->assign( "var2", true );

        $result = $this->engine->parse( "if/boolean/embeddedIf.html" );
        $this->assertEquals( "", $result );
    }

    public function testEmbeddedIf4() {
        $this->engine->assign( "var1", false );
        $this->engine->assign( "var2", false );

        $result = $this->engine->parse( "if/boolean/embeddedIf.html" );
        $this->assertEquals( "", $result );
    }

    public function testNoTrailingSpace1() {
        $this->engine->assign("var", true );

        $result = $this->engine->parse( "if/boolean/noTrailingSpace.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testNoTrailingSpace2() {
        $this->engine->assign("var", false );

        $result = $this->engine->parse( "if/boolean/noTrailingSpace.html" );

        $this->assertEquals( "", $result );
    }

    public function testNumberInputError() {
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( "TEMPLATE ERROR: Value '1234' could not be evaluated to a boolean value." );
        $this->engine->assign("testVar", 1234 );

        $this->engine->parse( "if/boolean/viaVar.html" );
    }

    public function testStringInputError() {
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( "TEMPLATE ERROR: Value 'stringWert' could not be evaluated to a boolean value." );
        $this->engine->assign("testVar", "stringWert" );

        $this->engine->parse( "if/boolean/viaVar.html" );
    }

    public function testHardcodedTrue() {
        $result = $this->engine->parse( "if/boolean/hardCodedTrue.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testHardcodedFalse() {
        $result = $this->engine->parse( "if/boolean/hardCodedFalse.html" );

        $this->assertEquals( "", $result );
    }

    public function testIfBooleanVarTrue() {
        $this->engine->assign("testVar", true );

        $result = $this->engine->parse( "if/boolean/viaVar.html" );
        $this->assertEquals( "yes", $result );
    }

    public function testIfBooleanVarFalse() {
        $this->engine->assign("testVar", false );

        $result = $this->engine->parse( "if/boolean/viaVar.html" );
        $this->assertEquals( "", $result );
    }

    public function testIfElseWithIfPartTrue() {
        $this->engine->assign("testVar", true );

        $result = $this->engine->parse( "if/boolean/ifElse.html" );
        $this->assertEquals( "if", $result );
    }

    public function testIfElseWithIfPartFalse() {
        $this->engine->assign("testVar", false );

        $result = $this->engine->parse( "if/boolean/ifElse.html" );
        $this->assertEquals( "else", $result );
    }

    public function testNotTrue() {
        $this->engine->assign("testVar", true );

        $result = $this->engine->parse( "if/boolean/not.html" );
        $this->assertEquals( "", $result );
    }

    public function testNotFalse() {
        $this->engine->assign("testVar", false );

        $result = $this->engine->parse( "if/boolean/not.html" );
        $this->assertEquals( "yes", $result );
    }

    public function testVarNotSet() {
        $this->engine = new SimpleTemplateEngine( "./tests/templates/" );

        $result = $this->engine->parse( "if/boolean/viaVar.html" );

        $this->assertEquals( "", $result );
    }

    public function testMultipleIf1() {
        $this->engine->assign( "var1", true );
        $this->engine->assign( "var2", true );

        $result = $this->engine->parse( "if/boolean/multipleIf.html" );
        $this->assertEquals( "onetwo", $result );
    }

    public function testMultipleIf2() {
        $this->engine->assign( "var1", true );
        $this->engine->assign( "var2", false );

        $result = $this->engine->parse( "if/boolean/multipleIf.html" );
        $this->assertEquals( "one", $result );
    }

    public function testMultipleIf3() {
        $this->engine->assign( "var1", false );
        $this->engine->assign( "var2", true );

        $result = $this->engine->parse( "if/boolean/multipleIf.html" );
        $this->assertEquals( "two", $result );
    }

    public function testMultipleIf4() {
        $this->engine->assign( "var1", false );
        $this->engine->assign( "var2", false );

        $result = $this->engine->parse( "if/boolean/multipleIf.html" );
        $this->assertEquals( "", $result );
    }
}

?>
