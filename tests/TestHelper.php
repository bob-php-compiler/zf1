<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/*
 * Set error reporting to the level to which Zend Framework code must comply.
 */
error_reporting(E_ALL | E_STRICT);

define('TEST_ROOT_DIR', getcwd());

if (defined('__BPC__')) {
    set_include_path("tests" . PATH_SEPARATOR . get_include_path());
    if (include_once_silent(__DIR__ . '/TestConfiguration.php') === false) {
        require_once __DIR__ . '/TestConfiguration.php.dist';
    }
} else {
    /*
     * Determine the root, library, and tests directories of the framework
     * distribution.
     */
    $zfRoot        = realpath(dirname(dirname(__FILE__)));
    $zfCoreLibrary = "$zfRoot/library";
    $zfCoreTests   = "$zfRoot/tests";

    /*
     * Prepend the Zend Framework library/ and tests/ directories to the
     * include_path. This allows the tests to run out of the box and helps prevent
     * loading other copies of the framework code and tests that would supersede
     * this copy.
     */
    $path = array(
        $zfCoreLibrary,
        $zfCoreTests,
        get_include_path()
        );
    set_include_path(implode(PATH_SEPARATOR, $path));

    /*
     * Load the user-defined test configuration file, if it exists; otherwise, load
     * the default configuration.
     */
    if (is_readable($zfCoreTests . DIRECTORY_SEPARATOR . 'TestConfiguration.php')) {
        require_once $zfCoreTests . DIRECTORY_SEPARATOR . 'TestConfiguration.php';
    } else {
        require_once $zfCoreTests . DIRECTORY_SEPARATOR . 'TestConfiguration.php.dist';
    }

    /*
     * Unset global variables that are no longer needed.
     */
    unset($zfRoot, $zfCoreLibrary, $zfCoreTests, $path);
}

/**
 * Start output buffering, if enabled
 */
if (defined('TESTS_ZEND_OB_ENABLED') && constant('TESTS_ZEND_OB_ENABLED')) {
    ob_start();
}

// Suppress DateTime warnings
date_default_timezone_set(@date_default_timezone_get());
