<?php
/**
 * @author David Rogers <david@ethos-development.com>
 */

/**
 * @var array of arguments passed to the script
 */
$argv = ( $argc ? $argv : array() );

/**
 * @var string name of the $script executed, which is always the first argument.
 */
$script = array_shift($argv);

/**
 * @var array of "sanitized", collected arguments
 */
$args = array();

/**
 * Since this script can be include()d from another, we check that
 * the $flags haven't already been created.
 *
 * @var array of "flag" names, that is arguments that just represent a toggle
 */
$flags = ( isset($flags) ? $flags : array() );

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
        $value = ( in_array($current, $flags) ?
            true : next($argv)
        );

        if ( isset($args[$current]) )
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

/**
 * In order to play nice with other scripts, let's put the
 * $argv array back like we found it.
 */
array_unshift($argv, $script);

/**
 * Returning the $args allows them to be available in a
 * include()ing script with a simple assignment, i.e.
 *    $args = include('this-script.php');
 */
return $args;

