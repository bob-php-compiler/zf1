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
 * @package    Zend_Layout
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

require_once 'Zend/Layout/LayoutTest.php';
require_once 'Zend/Layout/HelperTest.php';
require_once 'Zend/Layout/PluginTest.php';
//require_once 'Zend/Layout/FunctionalTest.php';

/**
 * @category   Zend
 * @package    Zend_Layout
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Layout
 */
class Zend_Layout_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend Framework - Zend_Layout');

        $suite->addTestSuite('Zend_Layout_LayoutTest');
        $suite->addTestSuite('Zend_Layout_HelperTest');
        $suite->addTestSuite('Zend_Layout_PluginTest');
        //$suite->addTestSuite('Zend_Layout_FunctionalTest');

        return $suite;
    }
}
