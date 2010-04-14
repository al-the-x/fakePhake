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
abstract class Phake_Transaction_Abstract
{
    /**
     * @var mixed cached $_result of the transaction()
     * @see getResult() for public getter
     * @see _setResult() for protected setter
     */
    private $_result;


    /**
     * @return mixed $_result of the transaction(), which is publicly read-only
     */
    final public function getResult ( )
    {
        return $this->_result;
    } // END getResult

    
    /**
     * The _setResult() method is the preferred method for pushing output
     * into the private $_result instance variable. By default, multiple
     * calls to _setResult() will convert a scalar $_result to an array,
     * and all subsequent calls will push onto the array (which can later
     * be joined for output).
     *
     * @param mixed $value to set / add to $_result
     * @param boolean $overwrite the current $_result instead of appending
     * @return Phake_Transaction_Abstract for method chaining
     */
    protected function _setResult ( $value = null, $overwrite = false )
    {
        if ( is_null($this->_result) or $overwrite ) 
        {
            $this->_result = $value;
        }

        else
        {
            $this->_result = (array) $this->_result;

            array_push($this->_result, $value);
        }

        return $this; // for method chaining
    } // END result


    /**
     * The setup() method should be called immediately before the transaction()
     * to perform initialization logic. It is _not_ called automatically after 
     * the __construct()or completes and must be executed manually.
     *
     * @todo Considering firing setup() at the end of __construct()
     */
    public function setup ( ) { }


    /**
     * The cleanup() method should be called immediately after the transaction()
     * to perform, well... cleanup logic. It is _not_ called automatically by
     * anything and must be executed manually.
     *
     * @todo Consider firing cleanup() in __destruct()
     */
    public function cleanup ( ) { }


    /**
     * The transaction() method represents the meat of the script and is
     * required for the script to run, hence declaring it abstract. It's
     * recommended that any output will be placed into $_result, a read-only
     * instance variable that is returned by getResult()
     *
     * @see getResult()
     * @return Project_Transaction_Abstract for method chaining
     */
    abstract public function transaction ( );

} // END Phake_Transaction_Abstract

