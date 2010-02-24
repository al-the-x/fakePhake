<?php
/**
 * @author David Rogers <david@ethos-development.com>
 */

/**
 * @var array of arguments passed to the script
 */
$args = include('parse-args.php');

/**
 * @var string filename to read in from
 */
$infile = ( isset($args['--infile']) ? 
    $args['--infile'] : null 
);

/**
 * If the $infile isn't readable, then we should throw
 * an appropriate Exception to halt processing.
 */
if ( !is_readable($infile) ) throw new Exception(
    'Supplied input file does not exist: ' . $infile
);

/**
 * @var resource fopen() handle marked for reading
 */
$infile = fopen($infile, 'r');

/**
 * @var array of $data, which are associative arrays of $values indexed by $fields
 */
$data = array();

/**
 * @var array of field names extracted from the first line of the $infile
 */
$data['fields'] = fgetcsv($infile);

/**
 * Using an assignmen in the control loop here isn't my favorite tactic
 * but is extremely efficient.
 *
 * @var array of $values extracted from the current line of the $infile
 */
while ( $values = fgetcsv($infile) )
{
    /**
     * Combining the $fields and $values produces an appropriae associative
     * array of $values indexed by the $fields.
     */
    $data[] = array_combine($data['fields'], $values);
}

/**
 * By returning the $data, this script can be include()d in other scripts
 * and assigned to a variable directly, a la:
 *    $data = include('this-script.php');
 */
return $data;

