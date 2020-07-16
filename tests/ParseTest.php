<?php

include_once './tests/TemplateEngineTestBase.php';

class ParseTest extends TemplateEngineTestBase {

    public function testParseFileDoesNotExist() {
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( "ERROR: Template file to display 'nonExistingFile.html' does not exist." );

        $this->engine->parse( "nonExistingFile.html" );
    }
}

?>
