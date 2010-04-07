<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Version
 * @category Test_Cases
 */

require_once 'Phake/Version.php';

/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Version
 * @category Test_Cases
 */
class Phake_VersionTest
extends PHPUnit_Framework_TestCase
{
    public function test_getVersion ( )
    {
        $this->assertTrue(method_exists(
            'Phake_Version', 'getVersion'
        ));

        $this->assertSame('0.1', Phake_Version::getVersion());
    } // END test_getVersion
} // END Phake_VersionTest

