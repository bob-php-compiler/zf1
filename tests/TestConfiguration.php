<?php

// run create-mysql-3307-test-database.sh
define('TESTS_ZEND_DB_ADAPTER_PDO_MYSQL_ENABLED',   true);
define('TESTS_ZEND_DB_ADAPTER_MYSQL_USERNAME',      'root');
define('TESTS_ZEND_DB_ADAPTER_MYSQL_PASSWORD',      '123456');
define('TESTS_ZEND_DB_ADAPTER_MYSQL_PORT',          3307);

define('TESTS_ZEND_DB_ADAPTER_PDO_SQLITE_ENABLED', true);

if (   file_exists('/var/www/zend-http-client-files')
    || symlink(TEST_ROOT_DIR . '/Zend/Http/Client/_files', '/var/www/zend-http-client-files')
) {
    define('TESTS_ZEND_HTTP_CLIENT_BASEURI', 'http://localhost/zend-http-client-files/');
}

require_once __DIR__ . '/TestConfiguration.php.dist';
