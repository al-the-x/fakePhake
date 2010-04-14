<?php
/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Model
 * @category Models
 */

/**
 * @author David Rogers <david@ethos-development.com>
 * @package Phake_Model
 * @category Models
 */
class Phake_Model_Csv
extends Phake_Model_Abstract
{
    /**
     * Key for the "infile" option, which specifies the filename
     * to load() from for the current instance.
     */
    const OPTIONS_INFILE = 'infile';

    /**
     * Key for the "get-fields" option, which indicates that the
     * names of the $_fields are stored in the first line.
     */
    const OPTIONS_GETFIELDS = 'get-fields';

    /**
     * Key for the "load-file" option, which attempts to load() the "infile"
     * upon instantiation.
     */
    const OPTIONS_LOADFILE = 'load-file';

    /**
     * @var boolean was load() successful?
     */
    protected $_loaded = false;

    /**
     * @see Phake_Model_Abstract::$_defaults
     * @var array map of default $_Options for the class
     */
    protected $_defaults = array(
        /**
         * The first line of the CSV file should contain the field names...
         */
        self::OPTIONS_GETFIELDS => true,
        /**
         * Don't try to load() anything by default.
         */
        self::OPTIONS_LOADFILE => false,
    ); // END $_defaults


    protected function _init ( )
    {
        parent::_init();

        if ( $this->getOption(self::OPTIONS_LOADFILE) and $this->getOption(self::OPTIONS_INFILE) )
        {
            $this->load();
        }
    } // END _init


    /**
     * The toArray() method returns the array representation of the CSV data
     * that's kept internally in $_values.
     *
     * @return array representation of the CSV data.
     */
    public function toArray ( )
    {
        return (array) $this->_values;
    } // END toArray

    /**
     * The magic __toArray() method proxies to the toArray() method.
     *
     * @return array representation of the CSV data
     * @see toArray()
     */
    public function __toArray ( )
    {
        return $this->toArray();
    } // END __toArray


    /**
     * The isLoaded() method provides a read-only accessor to the protected
     * $_loaded instance variable.
     */
    public function isLoaded ( )
    {
        return $this->_loaded;
    } // END isLoaded()


    /**
     * The load() method fetches data from a file in the filesystem, but
     * this could always be refactored later to pull from a data source.
     *
     * @param string $filename to load from or NULL to use the "in-file" option
     * @return Phake_Model_Csv for method chaining
     * @throws Phake_Model_Exception if $filename is invalid
     */
    public function load ( $filename = null )
    {
        /**
         * If the $filename is not specified, then the "infile" option should
         * contain the value we're after. If no value exists for that key, then
         * the $_Options object will throw an appropriate Exception.
         */
        $filename = ( is_null($filename) ?
            $this->getOption(self::OPTIONS_INFILE) : $filename
        );

        /**
         * Always polite to "look before you leap"...
         */
        if ( !is_readable($filename) )
        {
            throw new Phake_Model_Exception(
                'Provided filename is not readable: ' . $filename
            );
        } 

        /**
         * Phake_Scripts_CsvLoad is a procedural script (hence the namespace) that
         * reads data from a CSV file and returns the $field_values and $field_names.
         *
         * @see Phake_Scripts_CsvLoad
         */
        list( $field_values, $field_names ) = Phake_Loader::loadScript('CsvLoad', array( 
            'get_fields' => $this->getOption(self::OPTIONS_GETFIELDS),
            'filename'   => $filename,
        ));

        /**
         * Ensure that $_values is always an array...
         */
        $this->_values = (array) $field_values;

        /**
         * Ensure that $_fields is a map of the $field_names to default values.
         */
        $this->_fields = array_combine(
            $field_names, array_fill(0, count($field_names), null)
        );

        $this->_loaded = true;

        return $this; // for method chaining
    } // END load


    /**
     * The dropColumn() method drops the specified $column from the CSV data
     * but operates on a clone of the current instance by default, unless the
     * $use_clone flag is passed FALSE.
     *
     * @param string|integer $column to drop from the CSV data
     * @param boolean $use_clone or operate on the original?
     * @return Phake_Model_Csv for method chaining; either $this or a clone
     */
    public function dropColumn ( $column, $use_clone = true )
    {
        if ( is_numeric($column) )
        {
            // do something
        }

        if ( !array_key_exists($column, $this->_fields) )
        {
            // throw Exception?
        }

        $Object = ( $clone ? 
            clone $this : $this
        );

        foreach ( $Object->_values as $row )
        {
            unset($row[$column]);
        }

        unset($Object->_fields[$column]);

        return $Object; // for method chaining
    } // END dropColumn


    /**
     * The find() method searches the $_values for the provided $criteria and
     * returns an array of matching rows (for now).
     *
     * @param array $criteria to find()
     * @return array of 0 or more "rows" from the CSV data
     */
    public function find ( array $criteria )
    {
        $matches = array();

        foreach ( $this->_values as $row )
        {
            foreach ( $criteria as $field => $value )
            {
                if ( $row[$field] == $value )
                {
                    array_push($matches, $row);
                }
            } // END foreach $criteria
        } // END foreach $_values

        return $matches;
    } // END find


    /**
     * The count() method returns the number of rows in the CSV data. If the
     * data hasn't been load()ed yet, that's _your_ problem. ;)
     *
     * @return integer count() of the "rows" in $_values
     */
    public function count ( )
    {
        return count($this->_values);
    } // END count

} // END Phake_Model_Csv

