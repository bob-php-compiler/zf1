#!/bin/bash

[ "$PHPUNIT" == "" ] && {
    echo "Usage 1: PHPUNIT=phpunit-4.8.36 ./run-all-tests.sh [TEST]"
    echo "Usage 2: PHPUNIT=phpunit-bpc ./run-all-tests.sh [TEST]"
    echo "Usage 3: PHPUNIT=\"phpunit-bpc --bpc=.\" ./run-all-tests.sh [TEST]"
    echo "Usage 4: PHPUNIT=./test ./run-all-tests.sh [TEST]"
    exit
}

# Zend/*/AllTests.php,Zend/*Test.php
# E_ALL = 32767

if [ "$1" == "" ]
then
    TEST_LIST="Zend/Acl/AllTests.php
               Zend/ConfigTest.php
               Zend/Config/AllTests.php
               Zend/Log/AllTests.php
               Zend/Cache/AllTests.php
               Zend/LoaderTest.php
               Zend/Loader/AllTests.php
               Zend/LocaleTest.php
               Zend/Locale/AllTests.php
               Zend/TranslateTest.php
               Zend/Translate/AllTests.php
               Zend/RegistryTest.php
               Zend/MimeTest.php
               Zend/Mime/AllTests.php
               Zend/Mail/AllTests.php
               Zend/Db/AllTests.php
               Zend/ViewTest.php
               Zend/View/AllTests.php
               Zend/PaginatorTest.php
               Zend/Paginator/AllTests.php
               Zend/Http/AllTests.php
               Zend/UriTest.php
               Zend/Uri/AllTests.php
               Zend/Controller/AllTests.php
               Zend/Layout/AllTests.php
               Zend/Oauth/AllTests.php
               Zend/Crypt/AllTests.php"
else
    TEST_LIST=$1
fi

for i in $TEST_LIST
do
    printf "\n\n\033[32;49;1m@@ $i @@\033[39;49;0m\n\n" >&2
    $PHPUNIT --bootstrap=TestHelper.php --stderr -d memory_limit=-1 -d error_reporting=32767 -d display_errors=1 $i
done
