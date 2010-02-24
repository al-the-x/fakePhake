<?php

require_once 'PHPUnit/Framework.php';

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
                'long-field' => 'really long value with
line breaks and
everything.',
                'after-long-field' => 'value after long field'
           ), // END line 0
        ), $values);
    } // END test_script

} // END Phake_Scripts_CsvLoadTest

