<?php
/**
 * Customer address format configuration filesystem loader.
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
namespace Magento\Customer\Model\Address\Config;

class Reader extends \Magento\Config\Reader\Filesystem
{
    /**
     * @param \Magento\Config\FileResolverInterface $fileResolver
     * @param \Magento\Customer\Model\Address\Config\Converter $converter
     * @param \Magento\Customer\Model\Address\Config\SchemaLocator $schemaLocator
     * @param \Magento\Config\ValidationStateInterface $validationState
     */
    public function __construct(
        \Magento\Config\FileResolverInterface $fileResolver,
        \Magento\Customer\Model\Address\Config\Converter $converter,
        \Magento\Customer\Model\Address\Config\SchemaLocator $schemaLocator,
        \Magento\Config\ValidationStateInterface $validationState
    ) {
        parent::__construct(
            $fileResolver, $converter, $schemaLocator, $validationState, 'address_formats.xml', array(
                '/config/format' => 'code'
        ));
    }
}