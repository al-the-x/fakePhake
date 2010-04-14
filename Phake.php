#!/usr/bin/env php
<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake
 * @category Bootstraps
 *
 * @todo Refactor into an Object for Pete's sake... O_O
 */

require 'Phake/Loader.php';
Phake_Loader::register();

$args = Phake_Loader::loadScript('GetArgs');

$commands = array();
$values = array();

if ( isset($args['--version']) or isset($args['-v']) )
{
    array_push($values, 'Phake version ' . Phake_Version::VERSION);
} // END if --version

if ( isset($args['--transaction']) )
{
    array_push($commands, array
    (
        'callback' => array('Phake_Transaction', 'factory'),
        'arguments' => $args['--transaction'],
    )); // END array_push()

} // END if --transaction

foreach ( $commands as $command )
{
    try 
    { 
        array_push($values, call_user_func_array($command['callback'], $command['arguments']));
    }

    catch (Phake_Exception $Error)
    {
        /**
         * @todo Push the Exception stack trace into the output, too, maybe if a --debug flag is set?
         */
        array_push($values, 'Exception Thrown: ' . $Error->getMessage(), "\n");
    } // END catch
} // END foreach

foreach ( $values as $value )
{
    echo $value, "\n\n";
} // END foreach $values
