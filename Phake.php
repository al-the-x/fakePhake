#!/usr/bin/env php
<?php

require 'Phake/Loader.php';
Phake_Loader::register();

$args = Phake_Loader::load('Phake_Scripts_GetArgs');
print_r($args);

try
{
    @list( $provider, $action ) = split('::', $args['--action']);

    $provider = 'Phake_Providers_' . ucfirst($provider);
    Phake_Loader::load($provider);

    $Provider = new $provider($args);

    $Provider->$action();
}

catch ( Phake_Providers_Exception $Error )
{
    if ( $Error->getCode() == Phake_Providers_Exception::METHOD_MISSING )
    {
        echo $Error->getMessage(), "\n";
    }

    else throw $Error;
} // END catch


