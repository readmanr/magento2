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
 * @package     Magento_Eav
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Enter description here...
 *
 * Properties:
 * - prefix
 * - pad_length
 * - pad_char
 * - last_id
 */
namespace Magento\Eav\Model\Entity\Increment;

abstract class AbstractIncrement extends \Magento\Object
    implements \Magento\Eav\Model\Entity\Increment\IncrementInterface
{
    public function getPadLength()
    {
        $padLength = $this->getData('pad_length');
        if (empty($padLength)) {
            $padLength = 8;
        }
        return $padLength;
    }

    public function getPadChar()
    {
        $padChar = $this->getData('pad_char');
        if (empty($padChar)) {
            $padChar = '0';
        }
        return $padChar;
    }

    public function format($id)
    {
        $result = $this->getPrefix();
        $result.= str_pad((string)$id, $this->getPadLength(), $this->getPadChar(), STR_PAD_LEFT);
        return $result;
    }

    public function frontendFormat($id)
    {
        return $id;
    }
}