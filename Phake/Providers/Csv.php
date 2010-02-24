<?php

require_once 'Phake/Providers/Abstract.php';

class Phake_Providers_Csv
extends Phake_Providers_Abstract
{
    protected $_options_defaults = array(
        'get-fields' => true,
    ); // END $_options_default

    protected $_data = array();


    public function toArray ( )
    {
        return $this->_data;
    } // END toArray

    public function __toArray ( )
    {
        return $this->toArray();
    } // END __toArray


    public function load ( $filename = null )
    {
        $filename = ( is_null($filename) ?
            $this->getOption('infile') : $filename
        );

        if ( !is_readable($filename) )
        {
            throw new Phake_Providers_Exception(
                'Provided filename is not readable: ' . $filename,
                Phake_Providers_Exception::FILESYSTEM_MISSING
            );
        } 

        $get_fields = $this->getOption('get-fields');

        list( $this->_data, $this->_fields ) = include 'Phake/Scripts/CsvLoad.php';

        return $this; // for method chaining
    } // END load


    public function drop ( $column )
    {
        if ( is_numeric($column) )
        {
            // do something
        }

        $index = array_search($column, $this->_fields);

        if ( false === $index )
        {
            // throw Exception
        }

        // return a copy of $this with $column removed...
    } // END drop

} // END Phake_Providers_Csv

