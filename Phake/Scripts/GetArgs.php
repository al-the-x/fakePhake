<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Scripts
 * @category Resource_Scripts
 */

/**
 * The array_peek() function grabs the next() element in the $array
 * but doesn't advance the internal pointer... Well, actually it resets
 * the counter afterward, but that's okay.
 *
 * @param array $array to peek() at
 * @return mixed next() value in $array
 */
function array_peek ( array &$array )
{
    $next = next($array);

    prev($array);

    return $next;
} // END array_peek


/**
 * The parse_args() function pulls arguments out of the provided $argv
 * array and returns a more useful PHP array representation.
 *
 * @param array $argv to parse arguments from
 * @return array of arguments parsed from $argv
 */
function parse_args ( &$argv )
{
    /**
     * @var array of "flag" names, that is arguments that just represent a toggle
     */
    $flags = array();

    /**
     * @var array of arguments passed to the script
     */
    $argv = (array) $argv;

    /**
     * @var array of "sanitized", collected arguments
     */
    $args = array();

    /**
     * Until we run out of $argv's...
     */
    while ( current($argv) !== false )
    {
        /**
         * @var string $current argument
         */
        $current = current($argv);

        /**
         * If the $current argument is an attribute indicator, then the
         * proceeding argument should be the associated value, unless
         * its one of the $flags...
         */
        if ( preg_match('/^--?/', $current) )
        {
            $next = array_peek($argv);

            if ( ($next === false) or preg_match('/^--?/', array_peek($argv)) )
            {
                array_push($flags, $current);
            }

            $value = ( in_array($current, $flags) ?
                    true : next($argv)
                    );

            if ( !is_bool($value) and isset($args[$current]) )
            {
                $args[$current] = (array) $args[$current];

                array_push($args[$current], $value);
            }

            /**
             * @var mixed boolean|string value of the argument
             */
            else $args[$current] = $value;

        } // END if (match)

        /**
         * Regular string arguments, those without an attribute
         * or flag indicator, are just push()ed into the $args.
         */
        else array_push($args, $current);

        /**
         * Don't forget to advance your iterator...
         */
        next($argv);
    } // END while ($current)

    return $args;

} // END parse_args

/**
 * To ensure that we're working with the GLOBAL $argv
 * and $argc variables...
 *
 * @var array $argv of arguments passed to the script
 */
global $argv;

/**
 * @var string name of the $script executed, which is always the first $argv
 */
$script = array_shift($argv);

/**
 * @var array of $args extracted from $argv
 */
$args = parse_args($argv);

/**
 * In order to play nice with other scripts, let's put the
 * $argv array back like we found it.
 */
array_unshift($argv, $script);

/**
 * Returning the $args allows them to be available in an
 * include()ing script with a simple assignment, i.e.
 *    $args = include('this-script.php');
 */
return $args;

