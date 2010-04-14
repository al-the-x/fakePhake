<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Loader
 * @category Test_Cases
 */

/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Loader
 * @category Test_Cases
 */
class Phake_LoaderTest
extends PHPUnit_Framework_TestCase
{
    public function setUp ( )
    {
        $this->fixture = new Phake_Loader;

        /**
         * The bootstrap.php file registers the Phake_Loader
         * for use in unit testing, so we have to unregister
         * it before we can test the functionality.
         */
        spl_autoload_unregister(array('Phake_Loader', 'load'));
    } // END setUp


    public function test_load ( )
    {
        $this->assertFalse(class_exists('Phake_Version', false));

        $this->fixture->load('Phake_Version');

        $this->assertTrue(class_exists('Phake_Version', false));
    } // END test_load


    public function test_autoload ( )
    {
        $this->assertFalse(class_exists('example_Class', false));

        $this->assertTrue(class_exists('example_Class', true));
    } // END test_autoload


    public function test_register ( )
    {
        $expected = array( 'Phake_Loader', 'load' );

        $this->assertNotContains(
            $expected, (array) spl_autoload_functions() 
        );

        $this->fixture->register();

        $actual = (array) spl_autoload_functions();

        $this->assertContains( $expected, $actual,
            'The spl_autoload stack should contain the Phake_Loader: ' . print_r($actual, true)
        );
    } // END test_register

} // END Phake_LoaderTest

