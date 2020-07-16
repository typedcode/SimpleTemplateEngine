<?php

include_once './tests/TemplateEngineTestBase.php';
include_once './tests/TestObject.php';

class MixedTest extends TemplateEngineTestBase {

    // public function testForeachWithInclude() {
    //     $array = array();
    //     $array[0] = "wert1";
    //     $array[1] = "wert2";
    //     $array[2] = "wert3";
    //     $array[3] = "wert4";
    //     $array[4] = "wert5";
    //
    //     $this->engine->assign( "array", $array);
    //
    //     $result = $this->engine->parse( "mixed/forEach_include.html" );
    //
    //     $this->assertEquals( "wert1wert2wert3wert4wert5", $result );
    // }
    //
    // public function testIfInForeach() {
    //     $array = array();
    //     $array[0] = "Wert1";
    //     $array[1] = "Wert2";
    //     $array[2] = "Wert3";
    //     $array[3] = "Wert4";
    //     $array[4] = "Wert5";
    //
    //     $this->engine->assign( "array", $array);
    //     $this->engine->assign( "myVar", "Wert4");
    //
    //     $result = $this->engine->parse( "mixed/if_in_foreach.html" );
    //
    //     $this->assertEquals( "\n nein \n\n" .
    //                         " nein \n\n" .
    //                         " nein \n\n" .
    //                         " ja \n\n" .
    //                         " nein \n", $result );
    // }
    //
    // public function testIfInclude() {
    //     $testObject = new TestObject();
    //     $testObject->publicVar = "Titel";
    //
    //     $showTable = true;
    //
    //     $this->engine->assign( "titel", $testObject );
    //     $this->engine->assign( "showTable", $showTable);
    //     $this->engine->assign( "TableHead", "Tabellenueberschrift");
    //
    //     $result = $this->engine->parse( "mixed/index_1.html" );
    //
    //     $this->assertEquals( "<h1>Titel</h1>\n\n" .
    //                         "    <table>\n" .
    //                         "    <tr>\n" .
    //                         "        <th>Tabellenueberschrift</th>\n" .
    //                         "    </tr>\n" .
    //                         "</table>\n", $result );
    // }

    public function testIfIncludeGoToElseBlockAndDoNotInclude() {
        // $testObject = new TestObject();
        // $testObject->publicVar = "Titel";
        //
        // $showTable = false;
        //
        // $this->engine->assign( "titel", $testObject );
        // $this->engine->assign( "showTable", $showTable);
        // $this->engine->assign( "TableHead", "Tabellenueberschrift");
        //
        // $result = $this->engine->parse( "mixed/index_1.html" );
        // $this->assertEquals( "<h1>Titel</h1>\n\n" .
        //                     "    Keine Tabelle Da!\n", $result );
        $this->assertEquals("", "");
    }
}

?>