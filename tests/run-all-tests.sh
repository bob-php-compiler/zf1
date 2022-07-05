#!/bin/bash

[ "$PHPUNIT" == "" ] && {
    echo "Usage 1: PHPUNIT=phpunit-4.8.36 ./run-all-tests.sh"
    echo "Usage 2: PHPUNIT=phpunit-bpc ./run-all-tests.sh"
    echo "Usage 3: PHPUNIT=\"phpunit-bpc --bpc=.\" ./run-all-tests.sh"
    echo "Usage 4: PHPUNIT=./test ./run-all-tests.sh"
    exit
}

# Zend/*/AllTests.php,Zend/*Test.php
# E_ALL = 32767

set -x

$PHPUNIT --bootstrap=TestHelper.php --stderr -d memory_limit=-1 -d error_reporting=32767 -d display_errors=1 Zend/Acl/AllTests.php
