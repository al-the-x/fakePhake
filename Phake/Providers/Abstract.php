<?php

require_once 'Phake/Providers/Exception.php';

abstract class Phake_Providers_Abstract
{
    protected $_options_defaults = array();

    protected $_options = array();

    public function __construct ( $options = array() )
    {
        $this->setOptions($options);
    } // END __construct


    public function setOptions ( $options )
    {
        $this->_options = array_merge(
            $this->_options_defaults,
            array_intersect_key( $options, $this->_options_defaults )
        ); // END $this->_options

        return $this; // for method chaining
    } // END setOptions


    public function getOption ( $name )
    {
        if ( !in_array($name, $this->_options) )
        {
            throw new Phake_Providers_Exception(
                'Requested option does not exist: ' . $name,
                Phake_Providers_Exception::OPTION_MISSING
            );
        }

        return $this->_options[$name];
    } // END getOption


    public function getOptions ( )
    {
        return $this->_options;
    } // END getOptions


    public function __call ( $method, array $args = array() )
    {
        throw new Phake_Providers_Exception(
            'Requested action does not exist: ' . $method,
            Phake_Providers_Exception::METHOD_MISSING
        );
    } // END __call
} // END Phake_Providers_Abstract

