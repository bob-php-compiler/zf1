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
 * @package    Zend_View
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/** Zend_View_Helper_Cycle */
require_once 'Zend/View/Helper/Cycle.php';

/**
 * Test class for Zend_View_Helper_Cycle.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_View
 * @group      Zend_View_Helper
 */
class Zend_View_Helper_CycleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zend_View_Helper_Cycle
     */
    public $helper;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        $this->helper = new Zend_View_Helper_Cycle();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->helper);
    }

    public function testCycleMethodReturnsObjectInstance()
    {
        $cycle = $this->helper->cycle();
        $this->assertTrue($cycle instanceof Zend_View_Helper_Cycle);
    }

    public function testAssignAndGetValues()
    {
        $this->helper->assign(array('a', 1, 'asd'));
        $this->assertEquals(array('a', 1, 'asd'), $this->helper->getAll());
    }

    public function testCycleMethod()
    {
        $this->helper->cycle(array('a', 1, 'asd'));
        $this->assertEquals(array('a', 1, 'asd'), $this->helper->getAll());
    }

    public function testToString()
    {
        $this->helper->cycle(array('a', 1, 'asd'));
        $this->assertEquals('a', (string) $this->helper->toString());
    }

    public function testNextValue()
    {
        $this->helper->assign(array('a', 1, 3));
        $this->helper->next();
        $this->assertEquals('a', (string) $this->helper->current());
        $this->helper->next();
        $this->assertEquals(1, (string) $this->helper->current());
        $this->helper->next();
        $this->assertEquals(3, (string) $this->helper->current());
        $this->helper->next();
        $this->assertEquals('a', (string) $this->helper->current());
        $this->helper->next();
        $this->assertEquals(1, (string) $this->helper->current());
    }

    public function testPrevValue()
    {
        $this->helper->assign(array(4, 1, 3));
        $this->assertEquals(3, (string) $this->helper->prev());
        $this->assertEquals(1, (string) $this->helper->prev());
        $this->assertEquals(4, (string) $this->helper->prev());
        $this->assertEquals(3, (string) $this->helper->prev());
        $this->assertEquals(1, (string) $this->helper->prev());
    }

    public function testRewind()
    {
        $this->helper->assign(array(5, 8, 3));
        $this->helper->next();
        $this->assertEquals(5, (string) $this->helper->current());
        $this->helper->next();
        $this->assertEquals(8, (string) $this->helper->current());
        $this->helper->rewind();
        $this->helper->next();
        $this->assertEquals(5, (string) $this->helper->current());
        $this->helper->next();
        $this->assertEquals(8, (string) $this->helper->current());
    }

    public function testMixedMethods()
    {
        $this->helper->assign(array(5, 8, 3));
        $this->helper->next();
        $this->assertEquals(5, (string) $this->helper->current());
        $this->helper->next();
        $this->assertEquals(5, (string) $this->helper->prev());
    }

    public function testTwoCycles()
    {
        $this->helper->assign(array(5, 8, 3));
        $this->helper->next();
        $this->assertEquals(5, (string) $this->helper->current());
        $this->helper->cycle(array(2,38,1),'cycle2')->next();
        $this->assertEquals(2, (string) $this->helper->current());
        $this->helper->cycle()->next();
        $this->assertEquals(8, (string) $this->helper->current());
        $this->helper->setName('cycle2')->next();
        $this->assertEquals(38, (string) $this->helper->current());
    }

    public function testTwoCyclesInLoop()
    {
        $expected = array(5,4,2,3);
        $expected2 = array(7,34,8,6);
        for($i=0;$i<4;$i++) {
            $this->helper->cycle($expected)->next();
            $this->assertEquals($expected[$i], (string) $this->helper->current());
            $this->helper->cycle($expected2,'cycle2')->next();
            $this->assertEquals($expected2[$i], (string) $this->helper->current());
        }
    }
}
