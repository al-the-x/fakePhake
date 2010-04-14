<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Pattern
 * @category Design_Patterns
 */

/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Pattern
 * @category Design_Patterns
 */
class Phake_Pattern_Options
{
    /**
     * Exception message if an invalid option is specified to get()
     */
    const EXCEPTION_OPTION_INVALID = 'The specified option is invalid: ';

    /**
     * Exception message if __set() is attempted.
     */
    const EXCEPTION_OPTION_READONLY = 'This instance provides a read-only interface.';

    /**
     * @var array of $_options for this instance
     */
    private $_options = array();


    /**
     * The __construct()or accepts an array of $options and optional
     * $defaults, which it attempts to merge intelligently (for now
     * just array_merge()). This is the only method of writing to the
     * $_options instance variable.
     */
    public function __construct ( $options, $defaults = array() )
    {
        $this->_options = array_merge(
            (array) $defaults,
            (array) $options
        );
    } // END __construct


    /**
     * The _findOption() method is used to fetch an $option using
     * dotted syntax for nested elements, for example:
     *
     *     "some.dotted.option" = $_options[some][dotted][option]
     *
     * @param string $option to find
     * @return mixed value of $option or NULL if invalid
     */ 
    protected function _findOption ( $option )
    {
        /**
         * @var array of $indexes split from the $segments
         */
        $indexes = array();

        /**
         * @var string $name passed that can be modified
         */
        $segments = $option;

        /**
         * Splitting the $segments on the dot character yields a list
         * of $indexes that can be used to burrow into the $_options
         * array for the desired value. At each iteration, the $segments
         * are trimmed away until only one remains.
         */
        while ( strstr($segments, '.') )
        {
            list($indexes[], $segments ) = explode('.', $segments, 2);
        }

        /**
         * The final segment (or the only one, if no dotted $name was
         * passed) is pushed into the $indexes for use as the final key.
         */
        array_push($indexes, $segments);

        /**
         * @var mixed value of the $option we're after
         */
        $value = $this->_options;

        /**
         * Iterating over the $indexes allows us to drill down into the
         * $_options array recursively.
         */
        while ( !is_null(key($indexes)) )
        {
            /**
             * If we ever encounter an invalid index, we just return NULL
             * and let the client code figure out what to do next. This
             * catches _any_ invalid index.
             */
            if ( !isset($value[current($indexes)]) ) return null;

            /**
             * This is recursive logic: each value of $indexes is another
             * layer we wish to inspect, so we keep around that layer and
             * drill down by index each iteration.
             */
            $value = $value[current($indexes)];

            next($indexes); // don't forget to advance your iterator
        } // END while

        return $value;
    } // END _findOption


    /**
     * The has() method returns boolean if the requested $option
     * exists in $this instance.
     *
     * @param string $option to check
     * @return boolean if $this has() $option
     */
    public function has ( $option )
    {
        return ( !is_null($this->_findOption($option)) );
    } // END has


    /**
     * The get() method returns the value of the $option requested
     * or throws an appropriate Exception if invalid.
     *
     * @param string $option to get()
     * @return mixed value of $option if valid
     * @throws Phake_Pattern_Exception if $option is invalid
     */
    public function get ( $option )
    {
        $value = $this->_findOption($option);

        if ( is_null($value) )
        {
            throw new Phake_Pattern_Exception(
                self::EXCEPTION_OPTION_INVALID . $option
            );
        }
        
        return $value;
    } // END get

    /**
     * The magic __get() method just proxies to get().
     *
     * @see get()
     */
    public function __get ( $option )
    {
        return $this->get($option);
    } // END __get


    /**
     * The magic __set() method just throws an appropriate Exception
     * because Options are read-only, once set.
     *
     * @param string $option to set
     * @param mixed $value to set
     * @throws Phake_Pattern_Exception because Phake_Pattern_Options are read-only
     */
    public function __set ( $option, $value )
    {
        throw new Phake_Pattern_Exception(
            self::EXCEPTION_OPTION_READONLY
        );
    } // END __set
} // END Phake_Pattern_Options

