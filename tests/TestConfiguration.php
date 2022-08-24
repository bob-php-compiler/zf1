<?php

/**
 * sudo apt install dovecot-pop3d
 * /etc/dovecot/conf.d/10-mail.conf
 *
 *  mail_location = maildir:~/Maildir
 *  mail_location = mbox:~:INBOX=~/INBOX
 *
 * su vmail
 * cd
 * ./dovecot-create-new-account.sh mbox@zf.test 123456
 * cd mbox@zf.test/
 * rm -rf Maildir/
 * chmod 777 .
 * touch INBOX
 */

define('TESTS_ZEND_MAIL_SERVER_TESTDIR', '/home/vmail/mbox@zf.test');

define('TESTS_ZEND_MAIL_POP3_ENABLED',  true);
define('TESTS_ZEND_MAIL_POP3_USER',     'mbox@zf.test');
define('TESTS_ZEND_MAIL_POP3_PASSWORD', '123456');

define('TESTS_ZEND_MAIL_IMAP_ENABLED',  true);
define('TESTS_ZEND_MAIL_IMAP_USER',     'mbox@zf.test');
define('TESTS_ZEND_MAIL_IMAP_PASSWORD', '123456');

// run create-mysql-3307-test-database.sh
define('TESTS_ZEND_DB_ADAPTER_PDO_MYSQL_ENABLED',   true);
define('TESTS_ZEND_DB_ADAPTER_MYSQL_USERNAME',      'root');
define('TESTS_ZEND_DB_ADAPTER_MYSQL_PASSWORD',      '123456');
define('TESTS_ZEND_DB_ADAPTER_MYSQL_PORT',          3307);

define('TESTS_ZEND_DB_ADAPTER_PDO_SQLITE_ENABLED', true);

define('TESTS_ZEND_AUTH_ADAPTER_DBTABLE_PDO_SQLITE_ENABLED', true);

if (   file_exists('/var/www/zend-http-client-files')
    || symlink(TEST_ROOT_DIR . '/Zend/Http/Client/_files', '/var/www/zend-http-client-files')
) {
    define('TESTS_ZEND_HTTP_CLIENT_BASEURI', 'http://localhost/zend-http-client-files/');
}

require_once __DIR__ . '/TestConfiguration.php.dist';
