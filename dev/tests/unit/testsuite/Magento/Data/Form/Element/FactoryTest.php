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
 * obtain it through the world-wide-web, please send an e-mail
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_Data
 * @subpackage  integration_tests
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Tests for \Magento\Data\Form\Element\Factory
 */
namespace Magento\Data\Form\Element;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_objectManagerMock;

    /**
     * @var \Magento\Data\Form\Element\Factory
     */
    protected $_factory;

    protected function setUp()
    {
        $this->_objectManagerMock = $this->getMock('Magento\ObjectManager\ObjectManager',
            array('create'), array(), '', false);
        $this->_factory = new \Magento\Data\Form\Element\Factory($this->_objectManagerMock);
    }

    /**
     * @param string $type
     * @dataProvider createPositiveDataProvider
     */
    public function testCreatePositive($type)
    {
        $className = 'Magento\Data\Form\Element\\' . ucfirst($type);
        $elementMock = $this->getMock($className, array(), array(), '', false);
        $this->_objectManagerMock->expects($this->once())
            ->method('create')
            ->with($className, array())
            ->will($this->returnValue($elementMock));
        $this->assertSame($elementMock, $this->_factory->create($type));
    }

    /**
     * @param string $type
     * @dataProvider createPositiveDataProvider
     */
    public function testCreatePositiveWithNotEmptyConfig($type)
    {
        $config = array('attributes' => array('attr1' => 'attr1', 'attr2' => 'attr2'));
        $className = 'Magento\Data\Form\Element\\' . ucfirst($type);
        $elementMock = $this->getMock($className, array(), array(), '', false);
        $this->_objectManagerMock->expects($this->once())
            ->method('create')
            ->with($className, $config)
            ->will($this->returnValue($elementMock));
        $this->assertSame($elementMock, $this->_factory->create($type, $config));
    }

    /**
     * @return array
     */
    public function createPositiveDataProvider()
    {
        return array(
            'button' => array('button'),
            'checkbox' => array('checkbox'),
            'checkboxes' => array('checkboxes'),
            'column' => array('column'),
            'date' => array('date'),
            'editablemultiselect' => array('editablemultiselect'),
            'editor' => array('editor'),
            'fieldset' => array('fieldset'),
            'file' => array('file'),
            'gallery' => array('gallery'),
            'hidden' => array('hidden'),
            'image' => array('image'),
            'imagefile' => array('imagefile'),
            'label' => array('label'),
            'link' => array('link'),
            'multiline' => array('multiline'),
            'multiselect' => array('multiselect'),
            'note' => array('note'),
            'obscure' => array('obscure'),
            'password' => array('password'),
            'radio' => array('radio'),
            'radios' => array('radios'),
            'reset' => array('reset'),
            'select' => array('select'),
            'submit' => array('submit'),
            'text' => array('text'),
            'textarea' => array('textarea'),
            'time' => array('time'),
        );
    }

    /**
     * @param string $type
     * @dataProvider createExceptionReflectionExceptionDataProvider
     * @expectedException \ReflectionException
     */
    public function testCreateExceptionReflectionException($type)
    {
        $this->_objectManagerMock->expects($this->once())
            ->method('create')
            ->with($type, array())
            ->will($this->throwException(new \ReflectionException()));
        $this->_factory->create($type);
    }

    /**
     * @return array
     */
    public function createExceptionReflectionExceptionDataProvider()
    {
        return array(
            'factory' => array('factory'),
            'collection' => array('collection'),
            'abstract' => array('abstract'),
        );
    }

    /**
     * @param string $type
     * @dataProvider createExceptionInvalidArgumentDataProvider
     * @expectedException \InvalidArgumentException
     */
    public function testCreateExceptionInvalidArgument($type)
    {
        $elementMock = $this->getMock($type, array(), array(), '', false);
        $this->_objectManagerMock->expects($this->once())
            ->method('create')
            ->with($type, array())
            ->will($this->returnValue($elementMock));
        $this->_factory->create($type);
    }

    /**
     * @return array
     */
    public function createExceptionInvalidArgumentDataProvider()
    {
        return array(
            'Magento\Data\Form\Element\Factory' => array('Magento\Data\Form\Element\Factory'),
            'Magento\Data\Form\Element\Collection' => array('Magento\Data\Form\Element\Collection'),
        );
    }
}