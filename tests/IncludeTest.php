<?php

include_once './tests/TemplateEngineTestBase.php';

class IncludeTest extends TemplateEngineTestBase {

    public function testIncludeByVariable() {
        $this->engine->assign("toInclude", "include/toInclude.html");

        $result = $this->engine->parse( "include/byVar.html" );

        $this->assertEquals( "<p>content to include</p>", $result );
    }

    public function testIncludeByString() {
        $result = $this->engine->parse( "include/byString.html" );

        $this->assertEquals( "<p>content to include</p>", $result );
    }

    public function testTemplateNotFoundByString() {
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( "ERROR: Could not find Template 'myTemplateFile.tpl' in given Template directory." );

        $this->engine->parse( "include/byStringError.html" );
    }

    public function testIncludeNotFoundByVar() {
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( "ERROR: Could not find Template 'include/toIncludeNotExistant.html' in given Template directory." );

        $this->engine->assign("toInclude", "include/toIncludeNotExistant.html");
        $this->engine->parse( "include/byVar.html" );
    }

    public function testRecursiveInclude() {
        $this->engine->assign("contentSecond", "content second");

        $result = $this->engine->parse( "include/recursiveMain.html" );

        $this->assertEquals( "content first\n" .
                            "before var 'content second' after var\n" .
                            "content first", $result );
    }
}

?>
