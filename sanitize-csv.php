#!/usr/local/bin/php
<?php

$file = fopen('eurosport_daytona-import.csv', 'r');
$expected = fopen('images/expected.txt', 'w');
$missing  = fopen('images/missing.txt', 'w');

$lines = array();
$fields = fgetcsv($file);

while ( $values = fgetcsv($file) )
{
    $line = array_combine($fields, $values);

    $line['description'] = preg_replace('/Manufactured by: .*\n?/', '', $line['description']);
    $line['description'] = htmlentities($line['description'], null, 'UTF-8');

    $line['description'] = implode(' ', explode("\n", $line['description']));

    $image = ltrim($line['image'], '/');

    fputs($expected, $image . "\n");
    
    if ( !file_exists('images/' . $image) ) 
    {
        $alternate = preg_replace('/(.*)(\.jpg)/', '\1-1\2', $image);

        if ( !file_exists('images/' . $alternate) ) fputs($missing, $line['sku'] . ': ' . $image . "\n");

        else $image = $alternate;
    }

    $line['image'] = $line['small_image'] = $line['thumbnail'] = '/' . $image;

    $lines[] = $line;
} // END while

$file = fopen('new.csv', 'w');

foreach ( $lines as $line ) fputcsv($file, $line);

##var_dump($lines);
