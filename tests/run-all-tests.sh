#!/bin/bash

[ "$PHPUNIT" == "" ] && {
    echo "Usage: PHPUNIT=phpunit.phar ./run-all-tests.sh"
    exit
}

# Zend/*/AllTests.php,Zend/*Test.php
# $PHPUNIT --stderr -d memory_limit=-1 -d error_reporting=E_ALL\&E_STRICT -d display_errors=1 XXX.php
