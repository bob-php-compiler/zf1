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
 * @package    Zend_Application
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @category   Zend
 * @package    Zend_Application
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zf7696Bootstrap extends Zend_Application_Bootstrap_BootstrapAbstract
{
    public $bar;
    public $default;
    public $foo;

    public $barExecuted = 0;
    public $fooExecuted = 0;
    public $executedFooResource = false;
    public $executedFooBarResource = false;

    protected $_arbitraryValue;

    public function run()
    {
    }

    protected function _initFoo()
    {
        $this->fooExecuted++;
    }

    protected function _initBar()
    {
        $this->barExecuted++;
    }

    protected function _initBarbaz()
    {
        $o = new stdClass();
        $o->baz = 'Baz';
        return $o;
    }

    protected function _initFrontController()
    {
        $front = Zend_Controller_Front::getInstance();

        $moduleDir = __DIR__ . '/modules';
        $modules = array('bar', 'baz', 'default', 'foo', 'foo-bar', 'zfappbootstrap');
        $moduleControllerDirectoryName = $front->getModuleControllerDirectoryName();
        foreach ($modules as $module) {
            $front->addControllerDirectory(
                $moduleDir . '/' . $module . '/' . $moduleControllerDirectoryName,
                $module
            );
        }

        return $front;
    }

    public function setArbitrary($value)
    {
        $this->_arbitraryValue = $value;
        return $this;
    }

    public function getArbitrary()
    {
        return $this->_arbitraryValue;
    }
}
