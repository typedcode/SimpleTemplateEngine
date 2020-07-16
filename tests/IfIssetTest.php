<?php

include_once './tests/TemplateEngineTestBase.php';

class IfIssetTest extends TemplateEngineTestBase {

    public function testVarIssetTrue() {
        $this->engine->assign( "var1", "does not matter" );

        $result = $this->engine->parse( "if/isset/isset.html" );

        $this->assertEquals( "yes", $result );
    }

    public function testVarIssetFalse() {
        $result = $this->engine->parse( "if/isset/isset.html" );

        $this->assertEquals( "no", $result );
    }

    public function testVarBla() {
        $result = $this->engine->parse( "if/isset/isset_access.html" );

        $this->assertEquals( "", "" );
    }
}

?>