<?php

define( "HOME_DIR", "./" );

use PHPUnit\Framework\TestCase;
include_once HOME_DIR . 'SimpleTemplateEngine.php';


class TemplateEngineTestBase extends TestCase{

    protected $engine;

    public function setUp() : void {
        $this->engine = new SimpleTemplateEngine( HOME_DIR . "tests/templates/" );
    }
}

?>
