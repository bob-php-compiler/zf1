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
 * @package    Zend_Captcha
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

require_once 'Zend/Form/Element/Captcha.php';
require_once 'Zend/View.php';
require_once 'Zend/Captcha/Dumb.php';

/**
 * @category   Zend
 * @package    Zend_Captcha
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Captcha
 */
class Zend_Captcha_DumbTest extends PHPUnit_Framework_TestCase
{
    protected $element;
    protected $captcha;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        if (isset($this->word)) {
            unset($this->word);
        }

        $this->element = new Zend_Form_Element_Captcha(
            'captchaD',
            array(
                'captcha' => new Zend_Captcha_Dumb(array(
                                'sessionClass' => 'Zend_Captcha_DumbTest_SessionContainer'
                             ))
            )
        );
        $this->captcha =  $this->element->getCaptcha();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    public function tearDown()
    {
    }

    public function testRendersWordInReverse()
    {
        $id   = $this->captcha->generate();
        $word = $this->captcha->getWord();
        $html = $this->captcha->render(new Zend_View);
        $this->assertContains(strrev($word), $html);
        $this->assertNotContains($word, $html);
    }

    /**
     * @group ZF-11522
     */
    public function testDefaultLabelIsUsedWhenNoAlternateLabelSet()
    {
        $this->assertEquals('Please type this word backwards', $this->captcha->getLabel());
    }

    /**
     * @group ZF-11522
     */
    public function testChangeLabelViaSetterMethod()
    {
        $this->captcha->setLabel('Testing');
        $this->assertEquals('Testing', $this->captcha->getLabel());
    }

    /**
     * @group ZF-11522
     */
    public function testRendersLabelUsingProvidedValue()
    {
        $this->captcha->setLabel('Testing 123');

        $id   = $this->captcha->generate();
        $html = $this->captcha->render(new Zend_View);
        $this->assertContains('Testing 123', $html);
    }
}

class Zend_Captcha_DumbTest_SessionContainer
{
    protected static $_word;

    protected $setExpirationHops;
    protected $setExpirationSeconds;

    public function __get($name)
    {
        if ('word' == $name) {
            return self::$_word;
        }

        return null;
    }

    public function __set($name, $value)
    {
        if ('word' == $name) {
            self::$_word = $value;
        } else {
            $this->$name = $value;
        }
    }

    public function __isset($name)
    {
        if (('word' == $name) && (null !== self::$_word))  {
            return true;
        }

        return false;
    }

    public function __call($method, $args)
    {
        switch ($method) {
            case 'setExpirationHops':
            case 'setExpirationSeconds':
                $this->$method = array_shift($args);
                break;
            default:
        }
    }
}
