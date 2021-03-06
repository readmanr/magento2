<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Phrase\Renderer;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\ObjectManager|PHPUnit_Framework_MockObject_MockObject
     */
    protected $_objectManager;

    /**
     * @var \Magento\Phrase\Renderer\Factory
     */
    protected $_factory;

    protected function setUp()
    {
        $this->_objectManager = $this->getMock('Magento\ObjectManager', array(), array(), '', false);

        $objectManagerHelper = new \Magento\TestFramework\Helper\ObjectManager($this);
        $this->_factory = $objectManagerHelper->getObject('Magento\Phrase\Renderer\Factory', array(
            'objectManager' => $this->_objectManager,
        ));
    }

    public function testCreate()
    {
        $className = 'class-name';
        $rendererMock = $this->getMock('Magento\Phrase\RendererInterface');

        $this->_objectManager->expects($this->once())->method('get')->with($className)
            ->will($this->returnValue($rendererMock));

        $this->assertEquals($rendererMock, $this->_factory->create($className));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Wrong renderer class-name
     */
    public function testWrongRendererException()
    {
        $className = 'class-name';
        $rendererMock = $this->getMock('WrongInterface');

        $this->_objectManager->expects($this->once())->method('get')->with($className)
            ->will($this->returnValue($rendererMock));

        $this->_factory->create($className);
    }
}
