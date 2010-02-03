#!/usr/local/bin/php
<?php

require_once 'Zend/Db.php';

$Adapter = Zend_Db::factory('pdo_mysql', array(
    'dbname' => 'hrd001_vendors',
    'username' => 'root',
    'password' => null,
    'profiler' => true,
));

$features = array(
    'feature_1', 'feature_2', 'feature_3', 'feature_4', 'feature_5',
);

$identifiers = array(
    'item_name', 'product_type', 'product_category',
);

$Select = $Adapter->select()
    ->from('eurosport_daytona_original')
; // END $Select

// var_dump($Adapter->fetchRow($Select)); exit;

// foreach ( array($Adapter->fetchRow($Select)) as $row )
foreach ( $Adapter->fetchAll($Select) as $row )
{
    
    if ( $row['short_description'] )
    {
        $row['description'] .= "\n\n" . $row['short_description'];
    }
    
    if ( $row['in_depth'] )
    {
        $row['description'] .= $row['in_depth'] . "\n\n";
    }
    
    unset($row['in_depth']);
    
    if ( !$row['short_description'] )
    {
        $row['short_description'] = trim($row['description']);
    }

    foreach ( $features as $index )
    {
        if ( $row[$index] )
        {
            $row['description'] .= sprintf('* %s' . "\n", $row[$index]);
        }
    }

    $row['description'] = trim($row['description']);
    
    if ( !$row['description'] )
    {
        echo sprintf('product %d has no description', $row['part_number']), "\n";
    }

    if ( !$row['name'] )
    {
        $row['name'] = implode(' - ', array_intersect_key($row, array_flip($identifiers)));
    }
    
    $row['sku'] = 'ED-' . $row['part_number'];
    unset($row['product_code'], $row['part_number']);
    
    $Adapter->insert('eurosport_daytona_revised', array_intersect_key($row, array_flip(array(
        'sku', 'price', 'cost', 'weight', 'name', 'manufacturer', 'product_code', 
        'part_number', 'image_1', 'image_2', 'thumbnail', 'description', 'short_description',
    ))));
}
