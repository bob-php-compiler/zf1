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
 * @package    Zend_Controller
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

require_once 'Zend/Controller/Action/Helper/Json.php';

require_once 'Zend/Controller/Action/HelperBroker.php';
require_once 'Zend/Controller/Action/Helper/ViewRenderer.php';
require_once 'Zend/Controller/Front.php';
require_once 'Zend/Controller/Response/Http.php';
require_once 'Zend/Layout.php';

/**
 * Test class for Zend_Controller_Action_Helper_Json
 *
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Controller
 * @group      Zend_Controller_Action
 * @group      Zend_Controller_Action_Helper
 */
class Zend_Controller_Action_Helper_JsonTest extends PHPUnit_Framework_TestCase
{
    protected $response;
    protected $viewRenderer;
    protected $helper;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        Zend_Controller_Action_Helper_JsonTest_Layout::resetMvcInstance();

        $this->response = new Zend_Controller_Response_Http();
        $this->response->headersSentThrowsException = false;

        $front = Zend_Controller_Front::getInstance();
        $front->resetInstance();
        $front->setResponse($this->response);

        $this->viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        Zend_Controller_Action_HelperBroker::addHelper($this->viewRenderer);
        $this->helper = new Zend_Controller_Action_Helper_Json();
        $this->helper->suppressExit = true;
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

    public function verifyJsonHeader()
    {
        $headers = $this->response->getHeaders();

        $found = false;
        foreach ($headers as $header) {
            if ('Content-Type' == $header['name']) {
                $found = true;
                $value = $header['value'];
                break;
            }
        }
        $this->assertTrue($found);
        $this->assertEquals('application/json', $value);
    }

    public function testJsonHelperSetsResponseHeader()
    {
        $this->helper->encodeJson('foobar');
        $this->verifyJsonHeader();
    }

    public function testJsonHelperReturnsJsonEncodedString()
    {
        $data = $this->helper->encodeJson(array('foobar'));
        $this->assertTrue(is_string($data));
        $this->assertEquals(array('foobar'), Json_decode($data, true));
    }

    public function testJsonHelperDisablesLayoutsAndViewRendererByDefault()
    {
        $layout = Zend_Layout::startMvc();
        $this->assertTrue($layout->isEnabled());
        $this->assertFalse($this->viewRenderer->getNoRender());
        $this->testJsonHelperReturnsJsonEncodedString();
        $this->assertFalse($layout->isEnabled());
        $this->assertTrue($this->viewRenderer->getNoRender());
    }

    public function testJsonHelperDoesNotDisableLayoutsAndViewRendererWhenKeepLayoutFlagTrue()
    {
        $layout = Zend_Layout::startMvc();
        $this->assertTrue($layout->isEnabled());
        $this->assertFalse($this->viewRenderer->getNoRender());
        $data = $this->helper->encodeJson(array('foobar'), true);
        $this->assertTrue($layout->isEnabled());
        $this->assertFalse($this->viewRenderer->getNoRender());
    }

    public function testSendJsonSendsResponse()
    {
        $this->helper->sendJson(array('foobar'));
        $this->verifyJsonHeader();
        $response = $this->response->getBody();
        $this->assertSame(array('foobar'), json_decode($response, true));
    }

    public function testDirectProxiesToSendJsonByDefault()
    {
        $this->helper->direct(array('foobar'));
        $this->verifyJsonHeader();
        $response = $this->response->getBody();
        $this->assertSame(array('foobar'), json_decode($response, true));
    }

    public function testCanPreventDirectSendingResponse()
    {
        $data = $this->helper->direct(array('foobar'), false);
        $this->assertSame(array('foobar'), json_decode($data, true));
        $this->verifyJsonHeader();
        $response = $this->response->getBody();
        $this->assertTrue(empty($response));
    }

    public function testCanKeepLayoutsWhenUsingDirect()
    {
        $layout = Zend_Layout::startMvc();
        $data = $this->helper->direct(array('foobar'), false, true);
        $this->assertTrue($layout->isEnabled());
        $this->assertFalse($this->viewRenderer->getNoRender());
    }

    /**
     * @group ZF-10977
     */
    public function testEncodeJsonWillAcceptPreencodedJson()
    {
        $data = $this->helper->encodeJson(json_encode(array('f')), false, false);
        $this->assertEquals('["f"]', $data);
    }

    /**
     * @group ZF-10977
     */
    public function testSendJsonWillAcceptPreencodedJson()
    {
        $data = $this->helper->sendJson(json_encode(array('f')), false, false);
        $this->assertEquals('["f"]', $data);
    }

    /**
     * @group ZF-10977
     */
    public function testDirectWillAcceptPreencodedJson()
    {
        $data = $this->helper->direct(json_encode(array('f')), false, false, false);
        $this->assertEquals('["f"]', $data);
    }

    /**
     * @group ZF-10977
     */
    public function testSendingPreencodedJsonViaDirectWillStillSendHeaders()
    {
        $data = $this->helper->direct(json_encode(array('f')), false, false, false);
        $this->verifyJsonHeader();
    }

    /**
     * @group ZF-10977
     */
    public function testSendingPreencodedJsonViaSendJsonWillStillSendHeaders()
    {
        $data = $this->helper->sendJson(json_encode(array('f')), false, false);
        $this->verifyJsonHeader();
    }

    /**
     * @group ZF-10977
     */
    public function testSendingPreencodedJsonViaEncodeJsonWillStillSendHeaders()
    {
        $data = $this->helper->encodeJson(json_encode(array('f')), false, false);
        $this->verifyJsonHeader();
    }
}

/**
 * Zend_Layout subclass to allow resetting MVC instance
 */
class Zend_Controller_Action_Helper_JsonTest_Layout extends Zend_Layout
{
    public static function resetMvcInstance()
    {
        self::$_mvcInstance = null;
    }
}
