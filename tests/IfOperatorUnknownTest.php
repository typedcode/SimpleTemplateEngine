<?php

include_once './tests/TemplateEngineTestBase.php';
include_once './tests/TestObject.php';

class IfOperatorUnknownTest extends TemplateEngineTestBase {

    public function testUnknownOperator() {
        $this->expectException( Exception::class );
        $this->expectExceptionMessage( "ERROR in evaluationstring: \$var1 ll \$var2" );

        $this->engine->assign( "var1", "value1" );
        $this->engine->assign( "var2", "value2" );

        $this->engine->parse( "if/operators/unknownOperator.html" );
    }
}

?>