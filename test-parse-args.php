<?php
/**
 * @author David Rogers <david@ethos-development.com>
 */

$argv = array(
    'this-script.php',
    '--flag',
    '--argument', 'value',
    'non-argument',
    '0',
    'non-arg-after-0',
    '--repeated-arg', 'something',
    '--repeated-arg', 'something-else',
);

$flags = array( '--flag' );

$actual = include('parse-args.php');

$expected = array(
    '--flag' => true,
    '--argument' => 'value',
    'non-argument', 
    '0', 'non-arg-after-0',
    '--repeated-arg' => array(
        'something',
        'something-else',
    ),
);

foreach ( $expected as $index => $value )
{
    @assert('( isset($actual[$index]) and ($value == $actual[$index]) )') or var_dump(array(
        'index: ' => $index,
        'value: ' => $value,
        'actual: ' => isset($actual[$index]) ? $actual[$index] : null,
    ));
}

@assert('$expected == $actual') or var_dump(array(
    'expected' => $expected,
    'actual' => $actual,
));

return 0;
