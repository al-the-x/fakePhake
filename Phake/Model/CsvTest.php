<?php

class Phake_Model_CsvTest
extends PHPUnit_Framework_TestCase
{
    public function setUp ( )
    {
        $this->fixture = new Phake_Model_Csv;
    } // END setUp


    public function test_getOptions ( )
    {
        $this->assertTrue(method_exists($this->fixture, 'getOptions'),
            'The $fixture should have a getOptions() method.'
        );

        $this->assertEquals(array(
            'get-fields' => true,
        ), $this->fixture->getOptions());
    } // END getOptions

    
    public function test_toArray ( )
    {
        $this->assertTrue(method_exists($this->fixture, 'toArray'),
            'The $fixture should have a toArray() method.'
        );

        $this->assertType('array', $this->fixture->toArray(),
            'The toArray() method should return an array.'
        );
    } // END test_toArray


    public function test_load ( )
    {
        $this->assertSame(array(), $this->fixture->toArray(),
            'Initially, the $fixture should be "empty".'
        );

        $this->assertSame($this->fixture,
            $this->fixture->load('example/sample.csv')
        );

        $this->assertSame(array(
            0 => array(
                'sample' => 'data',
            ), // END line 0
        ), $this->fixture->toArray());
    } // END test_load


    public function test_dropColumn ( )
    {
        $this->test_load();

        $Actual = $this->fixture->dropColumn('sample');

        $this->assertNotSame($this->fixture, $Actual,
            'The dropColumn() method should return a clone of the $fixture.'
        );


        $this->assertTrue($this->fixture->has('sample'),
            'The $fixture should still have the "sample" field.'
        );

        $this->assertFalse($Actual->has('sample'),
            'The clone should NOT have the "sample" field.'
        );
    } // END test_dropColumn

} // END Phake_Model_CsvTest

