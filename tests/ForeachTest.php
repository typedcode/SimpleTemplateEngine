<?php

include_once './tests/TemplateEngineTestBase.php';
include_once './tests/TestObject.php';

class ForeachTest extends TemplateEngineTestBase {

    public function testForeachOneElement() {
        $testObject1 = new TestObject();
        $testObject1->publicVar = "publicVar1";
        $testObject1->setForEachOutput( "method1" );

        $testArray = array();
        $testArray[0] = $testObject1;

        $this->engine->assign( "testArray", $testArray );

        $result = $this->engine->parse( "foreach/foreach_1.html" );

        $this->assertEquals( "<p>publicVar1</p><p>method1</p>", $result );
    }

    public function testForeachMultipleElements() {
        $testObject1 = new TestObject();
        $testObject1->publicVar = "publicVar1";
        $testObject1->setForEachOutput( "method1" );

        $testObject2 = new TestObject();
        $testObject2->publicVar = "publicVar2";
        $testObject2->setForEachOutput( "method2" );

        $testObject3 = new TestObject();
        $testObject3->publicVar = "publicVar3";
        $testObject3->setForEachOutput( "method3" );


        $testObject4 = new TestObject();
        $testObject4->publicVar = "publicVar4";
        $testObject4->setForEachOutput( "method4" );


        $testObject5 = new TestObject();
        $testObject5->publicVar = "publicVar5";
        $testObject5->setForEachOutput( "method5" );

        $testArray = array();
        $testArray[0] = $testObject1;
        $testArray[1] = $testObject2;
        $testArray[2] = $testObject3;
        $testArray[3] = $testObject4;
        $testArray[4] = $testObject5;

        $this->engine->assign( "testArray", $testArray );
        $result = $this->engine->parse( "foreach/foreach_1.html" );

        $this->assertEquals( "<p>publicVar1</p><p>method1</p><p>publicVar2</p><p>method2</p><p>publicVar3</p><p>method3</p><p>publicVar4</p><p>method4</p><p>publicVar5</p><p>method5</p>", $result );
    }

    public function testNoElementInArray() {
        $testArray = array();

        $this->engine->assign( "testArray", $testArray );

        $result = $this->engine->parse( "foreach/foreach_1.html" );

        $this->assertEquals( "", $result);
    }

    public function testFunctionInForeach() {
        $this->engine->assign( "testObject", new TestObject() );

        $result = $this->engine->parse( "foreach/foreach_2.html" );

        $this->assertEquals( "value1value2value3value4value5", $result);
    }

    public function testNestedForeach() {
        $valuesOne = array( 1.1, 1.2 );
        $valuesTwo = array( 2.1, 2.2, 2.3 );

        $this->engine->assign( "testArray", array( $valuesOne, $valuesTwo ) );

        $result = $this->engine->parse( "foreach/foreach_nested.html" );

        $this->assertEquals( "<p>1.1</p><p>1.2</p><p>2.1</p><p>2.2</p><p>2.3</p>", $result);
    }
}

?>