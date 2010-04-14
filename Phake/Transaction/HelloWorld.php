<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Transaction
 * @category Transaction_Scripts
 */

/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Transaction
 * @category Transaction_Scripts
 */
class Phake_Transaction_HelloWorld
extends Phake_Transaction_Abstract
{
    /**
     * A simple sample transaction() that pushes "Hello World" into
     * the $_result for output later. To see how this works from the
     * CLI, try running the following:
     *
     * $> phake --transaction Phake_Transaction_HelloWorld
     */
    public function transaction ( )
    {
        $this->setResult( 'Hello World!' );
    } // END transaction

} // END Phake_Transaction_HelloWorld

