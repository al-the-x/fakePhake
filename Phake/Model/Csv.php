<?php

class Phake_Model_Csv
extends Phake_Model_Abstract
{
    const OPTIONS_INFILE = 'infile';

    const OPTIONS_GETFIELDS = 'get-fields';


    protected $_defaults = array(
        self::OPTIONS_GETFIELDS => true,
    ); // END $_defaults


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
            $this->getOption(self::OPTIONS_INFILE) : $filename
        );

        if ( !is_readable($filename) )
        {
            throw new Phake_Model_Exception(
                'Provided filename is not readable: ' . $filename,
            );
        } 

        $get_fields = $this->getOption('get-fields');

        list( $this->_data, $this->_fields ) = include 'Phake/Scripts/CsvLoad.php';

        return $this; // for method chaining
    } // END load


    public function dropColumn ( $column )
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
    } // END dropColumn

} // END Phake_Model_Csv

