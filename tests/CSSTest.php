<?php

include_once './tests/TemplateEngineTestBase.php';

class CSSTest extends TemplateEngineTestBase {

    public function testAddingCSSPaths() {
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
        $this->engine->addCSSFilePath( "myCss/path.css" );

        $this->assertEquals( 1, sizeof( $this->engine->getCSSFilePaths() ) );
        $this->assertEquals( "myCss/path.css", $this->engine->getCSSFilePaths()[0] );

        $this->engine->addCSSFilePath( "myCss/path.css" );

        $this->assertEquals( 1, sizeof( $this->engine->getCSSFilePaths() ) );
        $this->assertEquals( "myCss/path.css", $this->engine->getCSSFilePaths()[0] );
    }

    public function testForeachCSSEmptyCSS() {
        $this->assertEquals( "beforeafter", $this->engine->parse( "foreachCSS/foreachCSS.html" ) );
    }

    public function testForeachCSSSingleCSSFile() {
        $this->engine->addCSSFilePath( "my/css/path.css" );
        $this->assertEquals( "before<link rel=\"stylesheet\" type=\"text/css\" href=\"my/css/path.css\">after",
                             $this->engine->parse( "foreachCSS/foreachCSS.html" ) );
    }

    public function testForeachCSSMultipleCSSFiles() {
        $this->engine->addCSSFilePath( "my/css/path.css" );
        $this->engine->addCSSFilePath( "my/css2/path2.css" );
        $this->engine->addCSSFilePath( "my/css3/path3.css" );
        $this->assertEquals( "before<link rel=\"stylesheet\" type=\"text/css\" href=\"my/css/path.css\">".
                                    "<link rel=\"stylesheet\" type=\"text/css\" href=\"my/css2/path2.css\">".
                                    "<link rel=\"stylesheet\" type=\"text/css\" href=\"my/css3/path3.css\">after",
                             $this->engine->parse( "foreachCSS/foreachCSS.html" ) );
    }

    public function testForeachCSSMultilineForeach() {
        $this->engine->addCSSFilePath( "my/css/path.css" );
        $this->assertEquals( "\n<link rel=\"stylesheet\" type=\"text/css\" href=\"my/css/path.css\">\n",
                             $this->engine->parse( "foreachCSS/foreachCSSMultiline.html" ) );
    }

    public function testAddEmptyString() {
        $this->engine->addCSSFilePath( "" );
        $this->assertEquals( "beforeafter",
                             $this->engine->parse( "foreachCSS/foreachCSS.html" ) );
        $this->assertTrue( empty( $this->engine->getCSSFilePaths() ) );
    }
}

?>
