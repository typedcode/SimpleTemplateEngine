<?php

include_once './tests/TemplateEngineTestBase.php';

class CSSTest extends TemplateEngineTestBase {

    public function testAddingCSSPaths() {
        $this->engine = new SimpleTemplateEngine();

        $this->assertEquals( 0, sizeof( $this->engine->getCSSFilePaths() ) );

        $this->engine->addCSSFilePath( "myCss/path.css" );

        $this->assertEquals( 1, sizeof( $this->engine->getCSSFilePaths() ) );
        $this->assertEquals( "myCss/path.css", $this->engine->getCSSFilePaths()[0] );

        $this->engine->addCSSFilePath( "myCss/path2.css" );

        $this->assertEquals( 2, sizeof( $this->engine->getCSSFilePaths() ) );
        $this->assertEquals( "myCss/path.css", $this->engine->getCSSFilePaths()[0] );
        $this->assertEquals( "myCss/path2.css", $this->engine->getCSSFilePaths()[1] );

    }

    public function testDontInsertSameValueTwice() {
        $this->engine = new SimpleTemplateEngine();

        $this->engine->addCSSFilePath( "myCss/path.css" );

        $this->assertEquals( 1, sizeof( $this->engine->getCSSFilePaths() ) );
        $this->assertEquals( "myCss/path.css", $this->engine->getCSSFilePaths()[0] );

        $this->engine->addCSSFilePath( "myCss/path.css" );

        $this->assertEquals( 1, sizeof( $this->engine->getCSSFilePaths() ) );
        $this->assertEquals( "myCss/path.css", $this->engine->getCSSFilePaths()[0] );
    }
}

?>
