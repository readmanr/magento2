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
 * @package     Magento_Backend
 * @subpackage  integration_tests
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\Backend\Controller\Router\Validator;

/**
 * @magentoAppArea adminhtml
 */
class DefaultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @magentoConfigFixture global/areas/adminhtml/frontName 0
     * @expectedException \InvalidArgumentException
     * @magentoAppIsolation enabled
     */
    public function testConstructWithEmptyAreaFrontName()
    {
        $dataHelperMock = $this->getMock('Magento\Backend\Helper\Data', array(), array(), '', false);
        $dataHelperMock->expects($this->once())->method('getAreaFrontName')->will($this->returnValue(null));

        $options = array(
            'areaCode' => \Magento\Core\Model\App\Area::AREA_ADMINHTML,
            'baseController' => 'Magento\Backend\Controller\AbstractAction',
            'backendData' => $dataHelperMock,
        );
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('Magento\Backend\Controller\Router\DefaultRouter', $options);
    }

    /**
     * @magentoConfigFixture global/areas/adminhtml/frontName backend
     * @magentoAppIsolation enabled
     */
    public function testConstructWithNotEmptyAreaFrontName()
    {
        $options = array(
            'areaCode'       => \Magento\Core\Model\App\Area::AREA_ADMINHTML,
            'baseController' => 'Magento\Backend\Controller\AbstractAction',
        );
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('Magento\Backend\Controller\Router\DefaultRouter', $options);
    }
}