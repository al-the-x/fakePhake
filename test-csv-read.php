<?php
/**
 * @author David Rogers <david@ethos-development.com>
 */

$argv = array(
    'this-script.php',
    '--infile', 'test-file.csv',
);

$expected = array(
    array(
        'field' => 'value',
        'long-field' => 'really long value with' . "\n" . 'line breaks and' . "\n" . 'everything.',
        'after-long-field' => 'value after long field',
    ),
);

$actual = include('csv-read.php');

@assert('$expected == $actual') or var_dump(array(
    'expected' => $expected,
    'actual' => $actual,
));

return 0;
