<?php

include_once './tests/TemplateEngineTestBase.php';

class JSTest extends TemplateEngineTestBase {

    public function testAddingJSPaths() {
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
        $this->engine->addJSFilePath( "myJs/path.js" );

        $this->assertEquals( 1, sizeof( $this->engine->getJSFilePaths() ) );
        $this->assertEquals( "myJs/path.js", $this->engine->getJSFilePaths()[0] );

        $this->engine->addJSFilePath( "myJs/path.js" );

        $this->assertEquals( 1, sizeof( $this->engine->getJSFilePaths() ) );
        $this->assertEquals( "myJs/path.js", $this->engine->getJSFilePaths()[0] );
    }

    public function testForeachJSEmptyJS() {
        $this->assertEquals( "beforeafter", $this->engine->parse( "foreachJS/foreachJS.html" ) );
    }

    public function testForeachJSSingleJSFile() {
        $this->engine->addJSFilePath( "my/js/path.js" );
        $this->assertEquals( "before<script src=\"my/js/path.js\"></script>after",
                             $this->engine->parse( "foreachJS/foreachJS.html" ) );
    }

    public function testForeachJSMultipleJSFiles() {
        $this->engine->addJSFilePath( "my/js/path.js" );
        $this->engine->addJSFilePath( "my/js2/path2.js" );
        $this->engine->addJSFilePath( "my/js3/path3.js" );
        $this->assertEquals( "before<script src=\"my/js/path.js\"></script>".
                                    "<script src=\"my/js2/path2.js\"></script>".
                                    "<script src=\"my/js3/path3.js\"></script>after",
                             $this->engine->parse( "foreachJS/foreachJS.html" ) );
    }
}

?>
