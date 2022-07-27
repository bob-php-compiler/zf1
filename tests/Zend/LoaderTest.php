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
 * @package    Zend_Loader
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * Zend_Loader
 */
require_once 'Zend/Loader.php';

/**
 * Zend_Loader_Autoloader
 */
require_once 'Zend/Loader/Autoloader.php';

/**
 * @category   Zend
 * @package    Zend_Loader
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Loader
 */
class Zend_LoaderTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // Store original autoloaders
        $this->loaders = spl_autoload_functions();
        if (!is_array($this->loaders)) {
            // spl_autoload_functions does not return empty array when no
            // autoloaders registered...
            $this->loaders = array();
        }

        // Store original include_path
        $this->includePath = get_include_path();

        $this->error = null;
        $this->errorHandler = null;
        Zend_Loader_Autoloader::resetInstance();
    }

    public function tearDown()
    {
        if ($this->errorHandler !== null) {
            restore_error_handler();
        }

        // Restore original autoloaders
        $loaders = spl_autoload_functions();
        if (is_array($loaders)) {
            foreach ($loaders as $loader) {
                spl_autoload_unregister($loader);
            }
        }

        if (is_array($this->loaders)) {
            foreach ($this->loaders as $loader) {
                spl_autoload_register($loader);
            }
        }

        // Retore original include_path
        set_include_path($this->includePath);

        // Reset autoloader instance so it doesn't affect other tests
        Zend_Loader_Autoloader::resetInstance();
    }

    public function setErrorHandler()
    {
        set_error_handler(array($this, 'handleErrors'), E_USER_NOTICE);
        $this->errorHandler = true;
    }

    public function handleErrors($errno, $errstr)
    {
        $this->error = $errstr;
    }

    /**
     * Tests that a class can be loaded from a well-formed PHP file
     */
    public function testLoaderClassValid()
    {
        $dir = implode(array(dirname(__FILE__), '_files', '_testDir1'), DIRECTORY_SEPARATOR);

        Zend_Loader::loadClass('Class1', $dir);
    }

    public function testLoaderInterfaceViaLoadClass()
    {
        try {
            Zend_Loader::loadClass('Zend_Controller_Dispatcher_Interface');
        } catch (Zend_Exception $e) {
            $this->fail('Loading interfaces should not fail');
        }
    }

    public function testLoaderLoadClassWithDotDir()
    {
        $dirs = array('.');
        try {
            Zend_Loader::loadClass('Zend_Version', $dirs);
        } catch (Zend_Exception $e) {
            $this->fail('Loading from dot should not fail');
        }
    }

    /**
     * Tests that an exception is thrown when a file is loaded but the
     * class is not found within the file
     */
    public function testLoaderClassNonexistent()
    {
        $dir = implode(array(dirname(__FILE__), '_files', '_testDir1'), DIRECTORY_SEPARATOR);

        try {
            Zend_Loader::loadClass('ClassNonexistent', $dir);
            $this->fail('Zend_Exception was expected but never thrown.');
        } catch (Zend_Exception $e) {
            $this->assertRegExp('/file(.*)does not exist or class(.*)not found/i', $e->getMessage());
        }
    }

    /**
     * Tests that an exception is thrown if the $dirs argument is
     * not a string or an array.
     */
    public function testLoaderInvalidDirs()
    {
        try {
            Zend_Loader::loadClass('Zend_Invalid_Dirs', new stdClass());
            $this->fail('Zend_Exception was expected but never thrown.');
        } catch (Zend_Exception $e) {
            $this->assertEquals('Directory argument must be a string or an array', $e->getMessage());
        }
    }

    /**
     * Tests that a class can be loaded from the search directories.
     */
    public function testLoaderClassSearchDirs()
    {
        $dirs = array();
        foreach (array('_testDir1', '_testDir2') as $dir) {
            $dirs[] = implode(array(dirname(__FILE__), '_files', $dir), DIRECTORY_SEPARATOR);
        }

        // throws exception on failure
        Zend_Loader::loadClass('Class1', $dirs);
        Zend_Loader::loadClass('Class2', $dirs);
    }

    /**
     * Tests that a class locatedin a subdirectory can be loaded from the search directories
     */
    public function testLoaderClassSearchSubDirs()
    {
        $dirs = array();
        foreach (array('_testDir1', '_testDir2') as $dir) {
            $dirs[] = implode(array(dirname(__FILE__), '_files', $dir), DIRECTORY_SEPARATOR);
        }

        // throws exception on failure
        Zend_Loader::loadClass('Class1_Subclass2', $dirs);
    }

    /**
     * Tests that the security filter catches illegal characters.
     */
    public function testLoaderClassIllegalFilename()
    {
        try {
            Zend_Loader::loadClass('/path/:to/@danger');
            $this->fail('Zend_Exception was expected but never thrown.');
        } catch (Zend_Exception $e) {
            $this->assertRegExp('/security(.*)filename/i', $e->getMessage());
        }
    }

    /**
     * Tests that loadFile() finds a file in the include_path when $dirs is null
     */
    public function testLoaderFileIncludePathEmptyDirs()
    {
        $saveIncludePath = get_include_path();
        set_include_path(implode(array($saveIncludePath, implode(array(dirname(__FILE__), '_files', '_testDir1'), DIRECTORY_SEPARATOR)), PATH_SEPARATOR));

        $this->assertTrue(Zend_Loader::loadFile('Class3.php', null));

        set_include_path($saveIncludePath);
    }

    /**
     * Tests that loadFile() finds a file in the include_path when $dirs is non-null
     * This was not working vis-a-vis ZF-1174
     */
    public function testLoaderFileIncludePathNonEmptyDirs()
    {
        $saveIncludePath = get_include_path();
        set_include_path(implode(array($saveIncludePath, implode(array(dirname(__FILE__), '_files', '_testDir1'), DIRECTORY_SEPARATOR)), PATH_SEPARATOR));

        $this->assertTrue(Zend_Loader::loadFile('Class4.php', implode(PATH_SEPARATOR, array('foo', 'bar'))));

        set_include_path($saveIncludePath);
    }

    /**
     * Tests that isReadable works
     */
    public function testLoaderIsReadable()
    {
        $this->assertTrue(Zend_Loader::isReadable(__FILE__));
        $this->assertFalse(Zend_Loader::isReadable(__FILE__ . '.foobaar'));

        // test that a file in include_path gets loaded, see ZF-2985
        $this->assertTrue(Zend_Loader::isReadable('Zend/Controller/Front.php'), get_include_path());
    }

    /**
     * @group ZF-8200
     */
    public function testLoadClassShouldAllowLoadingPhpNamespacedClasses()
    {
        if (version_compare(PHP_VERSION, '5.3.0') < 0) {
            $this->markTestSkipped('PHP < 5.3.0 does not support namespaces');
        }
        Zend_Loader::loadClass('\Zfns\Foo', array(dirname(__FILE__) . '/Loader/_files'));
    }

    /**
     * @group ZF-9100
     */
    public function testIsReadableShouldReturnTrueForAbsolutePaths()
    {
        set_include_path(dirname(__FILE__) . '../../');
        $path = dirname(__FILE__);
        $this->assertTrue(Zend_Loader::isReadable($path));
    }

    /**
     * In order to play nice with spl_autoload, an autoload callback should
     * *not* emit errors (exceptions are okay). ZF-2923 requests that this
     * behavior be applied, which counters the previous request in ZF-2463.
     *
     * As it is, the new behavior *will* hide parse and other errors. However,
     * a fatal error *will* be raised in such situations, which is as
     * appropriate or more appropriate than raising an exception.
     *
     * NOTE: Removed from test suite, as autoload functionality in Zend_Loader
     * is now deprecated.
     *
     * @see    http://framework.zend.com/issues/browse/ZF-2463
     * @group  ZF-2923
     * @return void
    public function testLoaderAutoloadShouldHideParseError()
    {
        if (isset($_SERVER['OS'])  &&  strstr($_SERVER['OS'], 'Win')) {
            $this->markTestSkipped(__METHOD__ . ' does not work on Windows');
        }
        $command = 'php -d include_path='
            . escapeshellarg(get_include_path())
            . ' Zend/Loader/AutoloadDoesNotHideParseError.php 2>&1';
        $output = shell_exec($command);
        $this->assertTrue(empty($output));
    }
     */
}
