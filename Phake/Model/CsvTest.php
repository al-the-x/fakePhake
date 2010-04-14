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


    public function test_isLoaded ( $expected = false )
    {
        $this->assertEquals($expected, $this->fixture->isLoaded(),
            'The $fixture ' . ( $expected ? 'should' : 'should NOT' ) . ' be load()ed.'
        );
        
        return $this; // for method chaining
    } // END test_isLoaded


    public function test_autoload ( )
    {
        $this->fixture = new Phake_Model_Csv(array(
            'infile' => 'example/simple.csv',
        ));

        $this->test_isLoaded(false);

        $this->fixture = new Phake_Model_Csv(array(
            'infile' => 'example/simple.csv',
            'load-file' => true,
        ));

        $this->test_isLoaded(true);
    } // END test_autoload


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


    public function provide_find ( )
    {
        return array(
            'find code = 10000' => array(
                array( 'code' => 10000 ),
                array( array( 'code' => 10000, 'value' => 'ten' ) ),
            ), // END dataset
            'find code = 15000' => array(
                array( 'code' => 15000 ),
                array( array( 'code' => 15000, 'value' => 'fifteen' ) ),
            ), // END dataset
            'find value = "twelve"' => array(
                array( 'value' => 'twelve'),
                array( array( 'code' => 12000, 'value' => 'twelve' ) ),
            ), // END dataset
            'criteria matches nothing' => array(
                array( 'value' => 'foo'),
                array( ),
            ), // END dataset
        ); // END datasets
    } // END provide_find


    /**
     * @dataProvider provide_find
     * @param array $criteria to find()
     * @param array $expected data returned from find()
     */
    public function test_find ( $criteria, $expected )
    {
        $this->assertTrue(method_exists($this->fixture, 'find'));
        
        $this->fixture->load('example/lengthy.csv');

        $actual = $this->fixture->find($criteria);

        $this->assertEquals($expected, $actual);

    } // END test_find
} // END Phake_Model_CsvTest

