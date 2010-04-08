<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Model
 * @category Test_Cases
 */

PHPUnit_Util_Filter::addFileToFilter(__FILE__);

/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Model
 * @category Test_Cases
 */
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


    public static function provide_load ( )
    {
        return array(
            'no filename should generate an Exception' => array( ),
            'the "simple" file' => array( 'example/simple.csv', array(
                array( 'simple' => 'data' ),
            )), // END dataset
            'the "complex" file' => array( 'example/complex.csv', array(
                0 => array(
                    'field' => 'value',
                    'long-field' => "really long value with\nline breaks and\neverything.",
                    'after-long-field' => 'value after long field',
                ), // END row 0
            )), // END dataset
        ); // END array
    } // END provide_load

    /**
     * @dataProvider provide_load
     * @param string $filename to load()
     * @param boolean|array $expected value of toArray() or FALSE to indicate an Exception
     */
    public function test_load ( $filename = 'nothing', $expected = false )
    {
        $this->assertSame(array(), $this->fixture->toArray(),
            'Initially, the $fixture should be "empty".'
        );

        if ( $expected === false )
        {
            $this->setExpectedException('Phake_Model_Exception');
        }

        $this->assertSame($this->fixture,
            $this->fixture->load($filename)
        );

        $this->assertSame($expected, $this->fixture->toArray(),
            'The $fixture should match the $expected array.'
        );
    } // END test_load


    public function test_dropColumn ( )
    {
        $this->test_load();

        $this->assertTrue($this->fixture->has('sample'),
            'The $fixture should have a "sample" column.'
        );

        $Actual = $this->fixture->dropColumn('sample');

        $this->assertNotSame($this->fixture, $Actual,
            'The dropColumn() method should return a clone of the $fixture, not the original.'
        );

        $this->assertTrue($Actual instanceof $this->fixture,
            'The object returned by dropColumn() should still of the same type as the $fixture.'
        );


        $this->assertTrue($this->fixture->has('sample'),
            'The $fixture should still have the "sample" field.'
        );

        $this->assertFalse($Actual->has('sample'),
            'The clone should NOT have the "sample" field.'
        );
    } // END test_dropColumn

} // END Phake_Model_CsvTest

