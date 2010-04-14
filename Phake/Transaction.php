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
class Phake_Transaction
{
    /**
     * The factory() method accepts the name of a single $transaction script
     * or an array of scripts and _execute()s them.
     *
     * @param string|Phake_Transacion_Abstract $transaction to _execute()
     * @param array $options to provide to the $transaction
     * @return mixed return value of $transaction or array of return values
     */
    final public static function factory ( $transaction, array $options = array() )
    {
        if ( is_array($transaction) )
        {
            /**
             * @todo Figure out how to pass $options to factory() with an array of $transaction scripts
             */
            return array_map(array(__CLASS__, '_execute'), $transaction);
        }

        return self::_execute($transaction, $options);
    } // END factory


    /**
     * The _execute() method accepts the name of and a set of $options for 
     * a $transaction script and _execute()s it.
     *
     * @param string|Phake_Transaction_Abstract $transaction script to _execute()
     * @param array $options to provide to the $transaction
     * @return mixed return value of $transaction
     */
    private static function _execute ( $transaction, array $options = array() )
    {
        $Transaction = new $transaction($options);

        $Transaction->setup();
        $Transaction->transaction();
        $Transaction->cleanup();

        return $Transaction->getResult();
    } // END execute()

} // END Phake_Transaction
