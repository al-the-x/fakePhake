<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Version
 * @category Test_Cases
 */

/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Version
 * @category Test_Cases
 */
class Phake_VersionTest
extends PHPUnit_Framework_TestCase
{
    public function test_VERSION ( )
    {
        $this->assertSame('0.1', Phake_Version::VERSION);
    } // END test_getVersion
} // END Phake_VersionTest

