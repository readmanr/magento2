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
 * @package     Magento_DesignEditor
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Block class for rendering design editor tree of files
 */
namespace Magento\DesignEditor\Block\Adminhtml\Editor\Tools\Files;

class Tree
    extends \Magento\Theme\Block\Adminhtml\Wysiwyg\Files\Tree
{
    /**
     * Override root node name of tree specific to design editor.
     *
     * @return string
     */
    public function getRootNodeName()
    {
        return __('CSS Editor ') . __($this->helper('Magento\Theme\Helper\Storage')->getStorageTypeName());
    }
}