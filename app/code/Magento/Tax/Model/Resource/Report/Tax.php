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
 * @package     Magento_Tax
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Tax report resource model
 */
namespace Magento\Tax\Model\Resource\Report;

class Tax extends \Magento\Reports\Model\Resource\Report\AbstractReport
{
    /**
     * @var \Magento\Tax\Model\Resource\Report\Tax\CreatedatFactory
     */
    protected $_createdAtFactory;

    /**
     * @var \Magento\Tax\Model\Resource\Report\Tax\UpdatedatFactory
     */
    protected $_updatedAtFactory;

    /**
     * @param \Magento\Core\Model\Logger $logger
     * @param \Magento\Core\Model\Resource $resource
     * @param \Magento\Core\Model\LocaleInterface $locale
     * @param \Magento\Reports\Model\FlagFactory $reportsFlagFactory
     * @param \Magento\Tax\Model\Resource\Report\Tax\CreatedatFactory $createdAtFactory
     * @param \Magento\Tax\Model\Resource\Report\Tax\UpdatedatFactory $updatedAtFactory
     */
    public function __construct(
        \Magento\Core\Model\Logger $logger,
        \Magento\Core\Model\Resource $resource,
        \Magento\Core\Model\LocaleInterface $locale,
        \Magento\Reports\Model\FlagFactory $reportsFlagFactory,
        \Magento\Tax\Model\Resource\Report\Tax\CreatedatFactory $createdAtFactory,
        \Magento\Tax\Model\Resource\Report\Tax\UpdatedatFactory $updatedAtFactory
    ) {
        $this->_createdAtFactory = $createdAtFactory;
        $this->_updatedAtFactory = $updatedAtFactory;
        parent::__construct($logger, $resource, $locale, $reportsFlagFactory);
    }

    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('tax_order_aggregated_created', 'id');
    }

    /**
     * Aggregate Tax data
     *
     * @param mixed $from
     * @param mixed $to
     * @return \Magento\Tax\Model\Resource\Report\Tax
     */
    public function aggregate($from = null, $to = null)
    {
        /** @var $createdAt \Magento\Tax\Model\Resource\Report\Tax\Createdat */
        $createdAt = $this->_createdAtFactory->create();
        /** @var $updatedAt \Magento\Tax\Model\Resource\Report\Tax\Updatedat */
        $updatedAt = $this->_updatedAtFactory->create();

        $createdAt->aggregate($from, $to);
        $updatedAt->aggregate($from, $to);
        $this->_setFlagData(\Magento\Reports\Model\Flag::REPORT_TAX_FLAG_CODE);

        return $this;
    }
}