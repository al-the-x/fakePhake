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
class Phake_Scripts_GetArgsTest
extends PHPUnit_Framework_TestCase
{
    public static function provide_arguments ( )
    {
        return array(
            'everything and the kitchen sink' => array(
                'arguments' => array(
                    'this-script.php',
                    '--flag',
                    '--named-argument', 'value',
                    'non-named-argument',
                    '0',
                    'non-named-arg-after-0',
                    '--repeated-arg', 'something',
                    '--repeated-arg', 'something-else',
                    '--repeated-flag',
                    '--repeated-flag',
                    '--terminal-flag',
                ), // END arguments
                'expected' => array(
                    '--flag' => true,
                    '--named-argument' => 'value',
                    'non-named-argument', 
                    '0', 'non-named-arg-after-0',
                    '--repeated-arg' => array(
                        'something',
                        'something-else',
                    ),
                    '--repeated-flag' => true,
                    '--terminal-flag' => true,
                ), // END expected
            ), // END default
        ); // END datasets
    }

    /**
     * @dataProvider provide_arguments
     * @param array $arguments to inject into GLOBAL $argv for processing
     * @param array $expected return value from GetArgs.php
     * @return Phake_Scripts_GetArgsTest for method chaining
     */
    public function test_expected ( $arguments, $expected )
    {
        /**
         * In order to inject the $arguments into the $argv array for the
         * script, we have to pull it in from the GLOBAL environment.
         *
         * @var array of arguments passed to the script
         */
        global $argv;

        /**
         * We inject the $arguments into the $argv array directly so that
         * we can control what GetArgs.php will parse. Otherwise, we'd get
         * the arguments passed to the unit testing script.
         */
        $argv = $arguments;

        /**
         * The GetArgs.php script returns the arguments that it parses out
         * of the $argv array, so we don't really need a $fixture, just a
         * capture of the output to compare to the $expected.
         */
        $actual = require 'GetArgs.php';

        $this->assertEquals($expected, $actual,
            'The GetArgs.php script should generate the $expected results.'
        );
        
        return $this; // for method chaining
    } // END test_expected
} // END Phake_Scripts_GetArgsTest

