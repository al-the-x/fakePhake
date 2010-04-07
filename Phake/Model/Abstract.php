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
abstract class Phake_Model_Abstract
{
    /**
     * The key name for the "storage" option
     */
    const OPTIONS_STORAGE = 'storage';

    /**
     * @var Phake_Model_Storage_Interface to use as $_Storage adapter
     */
    private $_Storage = null;

    /**
     * @var Phake_Pattern_Options for this instance
     */
    private $_Options = null;

    /**
     * @var array map of option keys to values to be used as defaults
     * @see _setOptions()
     */
    protected $_defaults = array();

    /**
     * @var array map of field names to Phake_Model_Field instances
     */
    protected $_fields = array(
    ); // END $_fields

    /**
     * @var array map of field names to altered (dirty) values
     */
    protected $_values = array();


    /**
     * The __construct()or for Model objects accepts an array-compatible set
     * of $options, which it processes via the _setOptions() method. It's
     * recommended that descendent classes process their class-specific
     * option keys in _setOptions(), as well, and leave the __construct()or
     * alone.
     *
     * @see Test_Model_Abstract for an example of capturing $options
     */
    public function __construct ( $options = array() )
    {
        $this->_setOptions((array) $options);
    } // END __construct


    /**
     * The _setOptions() method initializes the $_Options object with the
     * array of $options provided, merging them with the $_defaults defined
     * on the class.
     *
     * @see $_defaults
     * @param array $options to be stored into an Phake_Pattern_Options class
     * @return Phake_Model_Abstract for method chaining
     * @throws Phake_Model_Exception if $_Options is already set
     */
    protected function _setOptions ( array $options )
    {
        if ( $this->_Options instanceof Phake_Model_Options )
        {
            throw new Phake_Model_Exception(
                Phake_Model_Exception::EXISTING_OPTIONS
            );
        }

        $this->_Options = new Phake_Pattern_Options(
            $options, $this->_defaults
        );

        return $this; // for method chaining
    } // END _setOptions


    /**
     * The getOptions() method accepts an option $name in dotted syntax
     * and returns the value for that key. It proxies to the $_Options
     * object, which is an instance of Phake_Pattern_Options.
     *
     * @param string $name of the option to return
     * @throws Phake_Pattern_Exception if $name is invalid
     * @return mixed value of the option
     * @see Phake_Pattern_Options::get()
     */
    public function getOption ( $name )
    {
        return $this->_Options->get($name);
    } // END getOption


    /**
     * Return a boolean indicator of whether $this Model has() the requested $field
     * by checking {@link $_fields} for a matching key.
     *
     * @param string $field to test
     * @return boolean if $field exists in $this Model's $_fields.
     */
    public function has ( $field )
    {
        return array_key_exists($field, $this->_fields);
    } // END has


    /**
     * The _require() method enforces the existence of a $field by throwing an
     * appropriate Exception if it doesn't exist.
     *
     * @param string $field to test
     * @return Phake_Model_Abstract for method chaining
     * @throws Phake_Model_Exception if $field does not exist
     * @see has()
     */
    public function _require ( $field )
    {
        if ( !$this->has($field) )
        {
            throw new Phake_Model_Exception(
                Phake_Model_Exception::MISSING_FIELD . $field
            );
        }

        return $this; // for method chaining
    } // END _require


    /**
     * The get() method returns the value of the $field requested or throws an
     * appropriate Exception if $field doesn't exist.
     *
     * @param string $field name to get()
     * @return mixed value of $field
     * @throws Phake_Model_Exception if $field doesn't exist
     * @see _require()
     */
    public function get ( $field )
    {
        if ( $this->_require($field)->__isset($field) )
        {
            /**
             * If the $field has been set(), then the interesting value will be
             * in the $_values container.
             */
            return $this->_values[$field];
        }

        /**
         * Otherwise, return the default value from the $_fields container.
         */
        return $this->_fields[$field];
    } // END get


    /**
     * The _validate() method checks the $value passed against a set of validation
     * rules specified for $field and returns an appropriate boolean to indicate
     * pass or fail. It generally doesn't care whether the $field exists, so this
     * method can be used for any validity test the Model needs.
     *
     * @param string $field to _validate()
     * @param mixed $value to _validate()
     * @return boolean if $value _validate()s for $field
     */
    protected function _validate ( $field, $value )
    {
        return true;
    } // END _validate


    /**
     * The _filter() method alters the $value passed based on the filtering rules
     * setup in the Model for the $field specified. While _filter() is used by
     * set(), by itself it generally doesn't care whether the $field exists,
     * so this method can be used for any value sanitation that the Model might need.
     *
     * @param string $field name of the _filter()ing rules
     * @param mixed $value to _filter()
     * @return mixed _filter()ed $value
     */
    protected function _filter ( $field, $value )
    {
        return $value;
    } // END _filter


    /**
     * The set() method sets $field to the specified $value and returns the Model
     * object for method chaining or throws an appropriate Exception if $field
     * doesn't exist. It first attempts to _validate() the $value passed based
     * on the rules for $field (by default, none) and _filter()s the $value
     * appropriately.
     *
     * @param string $field name to set()
     * @param mixed $value of $field
     * @return Phake_Model_Abstract for method chaining
     * @throws Phake_Model_Exception if $field doesn't exist
     *
     * @see _require()
     * @see _validate()
     * @see _filter()
     */
    public function set ( $field, $value )
    {
        if ( $this->_require($field)->_validate($field, $value) )
        {
            $this->_values[$field] = $this->_filter($field, $value);
        }

        return $this; // for method chaining
    } // END set


    /**
     * The __isset() (magic) method returns a boolean TRUE / FALSE if the requested
     * $field exists via has() AND has been set() by looking at the $_values
     * property of the Model.
     *
     * @param string $field to test
     * @return boolean if $field exists and has been previously set()
     */
    public function __isset ( $field )
    {
        return ( $this->has($field) and isset($this->_values[$field]) );
    } // END __isset


    /**
     * The __unset() (magic) method will destroy a the value of the $field
     * specified, removing it from the $_values container.
     *
     * @param string $field to __unset()
     * @return Phake_Model_Abstract for method chaining
     */
    public function __unset ( $field )
    {
        /**
         * If we attempt to unset() an array key that doesn't exist, then PHP
         * will complain with a WARNING. Rather than suppressing errors with the
         * shaddap (@), which is poor practice, we check that the $field __isset()
         * first.
         */
        if ( $this->__isset($field) )
        {
            unset($this->_values[$field]);
        }

        return $this; // for method chaining
    } // END __unset


    /**
     * The _getStorage method returns the $_Storage adapter, after ensuring that
     * it has been correctly instantiated. Please don't access the $_Storage
     * property directly; use _getStorage() instead.
     *
     * @return Phake_Model_Storage_Interface
     */
    public function _getStorage ( )
    {
        /**
         * @var string classname to use for $_Storage objects
         */
        static $storageClass;

        /**
         * The static $storageClass variable should only ever be derived once for
         * an instance, after which it should be "cached" as a static value.
         */
        if ( is_null($storageClass) )
        {
            $storageClass = $this->getOption(self::OPTIONS_STORAGE);

            if ( is_object($storageClass) )
            {
                $Storage = $storageClass;
                $storageClass = get_class($Storage);
            }

            else if ( is_array($storageClass) )
            {
                @list( $storageClass, $options ) = $storageClass;
            }

            if ( !class_exists($storageClass) )
            {
                throw new Phake_Model_Exception(
                    Phake_Model_Exception::MISSING_STORAGE_CLASS . $storageClass
                );
            }
            
            $valid = ( isset($Storage) and ($Storage instanceof Phake_Model_Storage_Interface) );

            $valid = $valid or ( in_array('Phake_Model_Storage_Interface', class_parents($storageClass)) );

            if ( !$valid )
            {
                throw new Phake_Model_Exception(
                    Phake_Model_Exception::INVALID_STORAGE_CLASS . $storageClass
                );
            }
        } // END if no $storageClass yet

        /**
         * Instantiate the $_Storage adapter if it doesn't already exist (or
         * if it's an object that it shouldn't be.
         */
        if ( !($this->_Storage instanceof $storageClass) )
        {
            if ( isset($Storage) )
            {
                $this->_Storage = $Storage;
            }

            else
            {
                $this->_Storage = ( isset($options) ?
                    new $storageClass($options) : new $storageClass
                );
            }
        } // END if no $_Storage yet

        return $this->_Storage;
    } // END _getStorage


    /**
     * The save() method passes the changed $_values to the $_Storage adapter
     * so that it can persist the data appropriately.
     */
    public function save ( )
    {
        $this->_getStorage()->save((array) $this->_values);

        return $this;
    } // END save


    public function load ( )
    {
        $this->_getStorage()->load(array());

        return $this;
    } // END load

} // END Phake_Model_Abstract
