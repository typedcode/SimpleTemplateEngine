<?php

include_once './tests/TemplateEngineTestBase.php';

class ConstructorTest extends TemplateEngineTestBase {

    public function testEmptyConstructor() {
        $this->engine = new SimpleTemplateEngine();

        $this->assertEquals( "./", $this->engine->getTemplateDir() );
    }

    public function testNoTrailingSlash() {
        $this->engine = new SimpleTemplateEngine( "templatePath" );

        $this->assertEquals( "templatePath/", $this->engine->getTemplateDir() );
    }

    public function testTrailingSlash() {
        $this->engine = new SimpleTemplateEngine( "templatePath/" );

        $this->assertEquals( "templatePath/", $this->engine->getTemplateDir() );
    }
}

?>
