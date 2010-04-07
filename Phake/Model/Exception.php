<?php
/**
 * @license GPL3.0?
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Model
 * @category Exceptions
 */

/**
 * @license GPL3.0?
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Model
 * @category Exceptions
 */
class Phake_Model_Exception
extends Phake_Exception
{
    const MISSING_FIELD = 'The required field does not exist in this model: ';

    const EXISTENT_OPTIONS = 'The Options for this class have already been defined: ';

    const MISSING_STORAGE_CLASS = 'The specified storage class does not exist: ';

    const INVALID_STORAGE_CLASS = 'The specified storage must implement Phake_Model_Storage_Interface: ';

} // END Phake_Model_Exception
