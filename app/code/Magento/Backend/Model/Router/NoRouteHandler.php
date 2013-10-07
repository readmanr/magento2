<?php
/**
 * Backend no route handler
 *
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
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\Backend\Model\Router;

class NoRouteHandler implements \Magento\Core\Model\Router\NoRouteHandlerInterface
{
    /**
     * @var \Magento\Backend\Helper\Data
     */
    protected $_helper;

    /**
     * @param \Magento\Backend\Helper\Data $helper
     */
    public function __construct(\Magento\Backend\Helper\Data $helper)
    {
        $this->_helper = $helper;
    }

    /**
     * Check and process no route request
     *
     * @param \Magento\Core\Controller\Request\Http $request
     * @return bool
     */
    public function process(\Magento\Core\Controller\Request\Http $request)
    {
        $requestPathParams = explode('/', trim($request->getPathInfo(), '/'));
        $areaFrontName = array_shift($requestPathParams);

        if ($areaFrontName == $this->_helper->getAreaFrontName()) {

            $moduleName     = 'core';
            $controllerName = 'index';
            $actionName     = 'noRoute';

            $request->setModuleName($moduleName)
                ->setControllerName($controllerName)
                ->setActionName($actionName);

            return true;
        }

        return false;
    }
}