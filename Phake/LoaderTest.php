<?php

require_once 'PHPUnit/Framework.php';

require_once 'Phake/Loader.php';

class Phake_LoaderTest
extends PHPUnit_Framework_TestCase
{
    public function setUp ( )
    {
        $this->fixture = new Phake_Loader;
    } // END setUp


    public function test_load ( )
    {
        $this->assertFalse(class_exists('Phake', false));

        $this->fixture->load('Phake');

        $this->assertTrue(class_exists('Phake', false));
    } // END test_load


    public function test_register ( )
    {
        $this->assertNotSame(false, spl_autoload_functions(),
            'The SPL __autoload() method should be available.'
        );

        $this->assertNotContains(
            array('Phake_Loader', 'autoload'),
            (array) spl_autoload_functions() 
        );

        $this->fixture->register();

        $this->assertContains(
            array('Phake_Loader', 'autoload'),
            (array) spl_autoload_functions() 
        );
    } // END test_register

} // END Phake_LoaderTest

