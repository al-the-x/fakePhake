#!/usr/bin/env bash

BOOTSTRAP=''

if [ -f bootstrap.php ]
then
    BOOTSTRAP='--bootstrap=bootstrap.php'
fi

phpunit $BOOTSTRAP $@

