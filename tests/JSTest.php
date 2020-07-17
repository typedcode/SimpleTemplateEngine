<?php

include_once './tests/TemplateEngineTestBase.php';

class JSTest extends TemplateEngineTestBase {

    public function testAddingJSPaths() {
        $this->engine = new SimpleTemplateEngine();

        $this->assertEquals( 0, sizeof( $this->engine->getJSFilePaths() ) );

        $this->engine->addJSFilePath( "myJs/path.js" );

        $this->assertEquals( 1, sizeof( $this->engine->getJSFilePaths() ) );
        $this->assertEquals( "myJs/path.js", $this->engine->getJSFilePaths()[0] );

        $this->engine->addJSFilePath( "myJs/path2.js" );

        $this->assertEquals( 2, sizeof( $this->engine->getJSFilePaths() ) );
        $this->assertEquals( "myJs/path.js", $this->engine->getJSFilePaths()[0] );
        $this->assertEquals( "myJs/path2.js", $this->engine->getJSFilePaths()[1] );

    }

    public function testDontInsertSameValueTwice() {
        $this->engine = new SimpleTemplateEngine();

        $this->engine->addJSFilePath( "myJs/path.js" );

        $this->assertEquals( 1, sizeof( $this->engine->getJSFilePaths() ) );
        $this->assertEquals( "myJs/path.js", $this->engine->getJSFilePaths()[0] );

        $this->engine->addJSFilePath( "myJs/path.js" );

        $this->assertEquals( 1, sizeof( $this->engine->getJSFilePaths() ) );
        $this->assertEquals( "myJs/path.js", $this->engine->getJSFilePaths()[0] );
    }
}

?>
