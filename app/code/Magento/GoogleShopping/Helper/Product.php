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
 * @package     Magento_GoogleShopping
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Google Content Product Helper
 *
 * @category   Magento
 * @package    Magento_GoogleShopping
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Magento\GoogleShopping\Helper;

class Product extends \Magento\Core\Helper\AbstractHelper
{
    /**
     * Product attributes cache
     *
     * @var array
     */
    protected $_productAttributes;

    /**
     * Attribute labels by store ID
     *
     * @var array
     */
    protected $_attributeLabels;

    /**
     * Return Product attribute by attribute's ID
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param int $attributeId
     * @return null|\Magento\Catalog\Model\Entity\Attribute Product's attribute
     */
    public function getProductAttribute(\Magento\Catalog\Model\Product $product, $attributeId)
    {
        if (!isset($this->_productAttributes[$product->getId()])) {
            $attributes = $product->getAttributes();
            foreach ($attributes as $attribute) {
                $this->_productAttributes[$product->getId()][$attribute->getAttributeId()] = $attribute;
            }
        }

        return isset($this->_productAttributes[$product->getId()][$attributeId])
            ? $this->_productAttributes[$product->getId()][$attributeId]
            : null;
    }

    /**
     * Return Product Attribute Store Label
     * Set attribute name like frontend lable for custom attributes (which wasn't defined by Google)
     *
     * @param \Magento\Catalog\Model\Resource\Eav\Attribute $attribute
     * @param int $storeId Store View Id
     * @return string Attribute Store View Label or Attribute code
     */
    public function getAttributeLabel($attribute, $storeId)
    {
        $attributeId = $attribute->getId();
        $frontendLabel = $attribute->getFrontend()->getLabel();

        if (is_array($frontendLabel)) {
            $frontendLabel = array_shift($frontendLabel);
        }
        if (!isset($this->_attributeLabels[$attributeId])) {
            $this->_attributeLabels[$attributeId] = $attribute->getStoreLabels();
        }

        if (isset($this->_attributeLabels[$attributeId][$storeId])) {
            return $this->_attributeLabels[$attributeId][$storeId];
        } else if (!empty($frontendLabel)) {
            return $frontendLabel;
        } else {
            return $attribute->getAttributeCode();
        }
    }
}