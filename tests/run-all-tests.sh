#!/bin/bash

[ "$PHPUNIT" == "" ] && {
    echo "Usage: PHPUNIT=phpunit.phar ./run-all-tests.sh"
    exit
}

# Zend/*/AllTests.php,Zend/*Test.php
# E_ALL = 32767

$PHPUNIT --bootstrap=TestHelper.php --stderr -d memory_limit=-1 -d error_reporting=32767 -d display_errors=1 Zend/Acl/AllTests.php
