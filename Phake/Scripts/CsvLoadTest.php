<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Scripts
 * @category Test_Cases
 */

/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Scripts
 * @category Test_Cases
 */
class Phake_Scripts_CsvLoadTest
extends PHPUnit_Framework_TestCase
{
    public function test_script ( )
    {
        $get_fields = true;

        $filename = 'test-file.csv';

        list( $values, $fields ) = require 'Phake/Scripts/CsvLoad.php';

        $this->assertSame(array(
            'field', 
            'long-field',
            'after-long-field',
        ), $fields);

        $this->assertSame(array(
            0 => array(
                'field' => 'value', 
                'long-field' => "really long value with\nline breaks and\neverything.",
                'after-long-field' => 'value after long field'
           ), // END line 0
        ), $values);
    } // END test_script

} // END Phake_Scripts_CsvLoadTest

