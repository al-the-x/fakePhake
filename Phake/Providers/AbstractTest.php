<?php

require_once 'PHPUnit/Framework.php';

require_once 'Phake/Providers/AbstractFixture.php';

class Phake_Providers_AbstractTest
extends PHPUnit_Framework_TestCase
{
    public function setUp ( )
    {
        $this->fixture = new Phake_Providers_Fixture;
    } // END setUp

} // END Phake_Providers_AbstractTest
